<?php

namespace PhpDocBlockChecker;

use PhpParser\Comment\Doc;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;

/**
 * Uses Nikic/PhpParser to parse PHP files and find relevant information for the checker.
 * @package PhpDocBlockChecker
 */
class FileProcessor
{
    protected $file;
    protected $classes = [];
    protected $methods = [];

    /**
     * Load and parse a PHP file.
     * @param string $file
     * @param Parser $parser
     */
    public function __construct($file, Parser $parser)
    {
        $this->file = $file;

        try {
            $contents = file_get_contents($file);
            if ($contents === false) {
                return;
            }
            $stmts = $parser->parse($contents);

            if ($stmts === null) {
                return;
            }
            $this->processStatements($stmts);
        } catch (\Exception $ex) {
            // Take no action.
        }
    }

    /**
     * Return a list of class details from the given PHP file.
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Return a list of method details from the given PHP file.
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Looks for class definitions, and then within them method definitions, docblocks, etc.
     * @param array $statements
     * @param string $prefix
     * @return mixed
     */
    protected function processStatements(array $statements, $prefix = '')
    {
        $uses = [];

        foreach ($statements as $statement) {
            if ($statement instanceof Namespace_) {
                return $this->processStatements($statement->stmts, (string)$statement->name);
            }

            if ($statement instanceof Use_) {
                foreach ($statement->uses as $use) {
                    // polyfill
                    $alias = $use->alias;
                    if (null === $alias && method_exists($use, 'getAlias')) {
                        $alias = $use->getAlias();
                    }

                    $uses[(string)$alias] = (string)$use->name;
                }
            }

            if ($statement instanceof Class_) {
                $class = $statement;
                $fullClassName = $prefix . '\\' . $class->name;

                $this->classes[$fullClassName] = [
                    'file' => $this->file,
                    'line' => $class->getAttribute('startLine'),
                    'name' => $fullClassName,
                    'docblock' => $this->getDocblock($class, $uses),
                ];

                foreach ($statement->stmts as $method) {
                    if (!$method instanceof ClassMethod) {
                        continue;
                    }

                    $fullMethodName = $fullClassName . '::' . $method->name;

                    $type = $method->returnType;

                    if ($type instanceof NullableType) {
                        $type = $type->type->toString();
                    } elseif ($type !== null) {
                        $type = $type->toString();
                    }

                    if (isset($uses[$type])) {
                        $type = $uses[$type];
                    }

                    if ($type !== null) {
                        $type = strpos($type, '\\') === 0 ? substr($type, 1) : $type;
                    }

                    if ($method->returnType instanceof NullableType) {
                        $type = ['null', $type];
                        sort($type);
                    }

                    $thisMethod = [
                        'file' => $this->file,
                        'class' => $fullClassName,
                        'name' => $fullMethodName,
                        'line' => $method->getAttribute('startLine'),
                        'return' => $type,
                        'params' => [],
                        'docblock' => $this->getDocblock($method, $uses),
                        'has_return' => isset($method->stmts) ? $this->statementsContainReturn($method->stmts) : false,
                    ];

                    /** @var Param $param */
                    foreach ($method->params as $param) {
                        $type = $param->type;

                        if ($type instanceof NullableType) {
                            $type = $type->type->toString();
                        } elseif ($type !== null) {
                            $type = $type->toString();
                        }

                        if (isset($uses[$type])) {
                            $type = $uses[$type];
                        }

                        if ($type !== null) {
                            $type = strpos($type, '\\') === 0 ? substr($type, 1) : $type;
                        }

                        if (property_exists($param, 'default') &&
                            $param->default instanceof Expr &&
                            property_exists($param->default, 'name') &&
                            property_exists($param->default->name, 'parts') &&
                            $type !== null &&
                            'null' === $param->default->name->parts[0]
                        ) {
                            $type .= '|null';
                        }

                        $name = null;
                        // parser v3
                        if (property_exists($param, 'name')) {
                            $name = $param->name;
                        }
                        // parser v4
                        if (null === $name && property_exists($param, 'var') && property_exists($param->var, 'name')) {
                            $name = $param->var->name;
                        }

                        $thisMethod['params']['$' . $name] = $type;
                    }

                    $this->methods[$fullMethodName] = $thisMethod;
                }
            }
        }
    }

    /**
     * Recursively search an array of statements for a return statement.
     * @param array $statements
     * @return bool
     */
    protected function statementsContainReturn(array $statements)
    {
        foreach ($statements as $statement) {
            if ($statement instanceof Stmt\Return_) {
                return true;
            }

            if (empty($statement->stmts)) {
                continue;
            }

            if ($this->statementsContainReturn($statement->stmts)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find and parse a docblock for a given class or method.
     * @param Stmt $stmt
     * @param array $uses
     * @return array|null
     */
    protected function getDocblock(Stmt $stmt, array $uses = [])
    {
        $comments = $stmt->getAttribute('comments');

        if (is_array($comments)) {
            foreach ($comments as $comment) {
                if ($comment instanceof Doc) {
                    return $this->processDocblock($comment->getText(), $uses);
                }
            }
        }

        return null;
    }

    /**
     * Use Paul Scott's docblock parser to parse a docblock, then return the relevant parts.
     * @param string $text
     * @param array $uses
     * @return array
     */
    protected function processDocblock($text, array $uses = [])
    {
        $parser = new DocblockParser($text);

        if ($parser->isInheritDocComment()) {
            return ['inherit' => true];
        }

        $rtn = ['params' => [], 'return' => null];

        if (isset($parser->tags['param'])) {
            foreach ($parser->tags['param'] as $param) {
                $type = (string)$param['type'];

                $types = [];
                foreach (explode('|', $type) as $tmpType) {
                    if (isset($uses[$tmpType])) {
                        $tmpType = $uses[$tmpType];
                    }

                    $types[] = strpos($tmpType, '\\') === 0 ? substr($tmpType, 1) : $tmpType;
                }

                $rtn['params'][$param['var']] = implode('|', $types);
            }
        }

        if (isset($parser->tags['return'])) {
            $return = array_shift($parser->tags['return']);

            $type = $return['type'];

            $types = [];
            /** @var string $tmpType */
            foreach (explode('|', $type) as $tmpType) {
                if (isset($uses[$tmpType])) {
                    $tmpType = $uses[$tmpType];
                }

                $types[] = strpos($tmpType, '\\') === 0 ? substr($tmpType, 1) : $tmpType;
            }

            $rtn['return'] = implode('|', $types);
        }

        return $rtn;
    }
}
