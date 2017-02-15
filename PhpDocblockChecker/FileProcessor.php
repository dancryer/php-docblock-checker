<?php

namespace PhpDocblockChecker;

use PhpDocblockChecker\DocBlockParser;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\ParserFactory;

/**
 * Uses Nikic/PhpParser to parse PHP files and find relevant information for the checker.
 * @package PhpDocblockChecker
 */
class FileProcessor
{
    protected $file;
    protected $classes = [];
    protected $methods = [];

    /**
     * Load and parse a PHP file.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;

        try {
            $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
            $stmts = $parser->parse(file_get_contents($file));
            $this->processStatements($stmts);
        } catch (\Exception $ex) {}
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
                    $uses[$use->alias] = (string)$use->name;
                }
            }

            if ($statement instanceof Class_) {
                $class = $statement;
                $fullClassName = $prefix . '\\' . (string)$class->name;

                $this->classes[$fullClassName] = [
                    'file' => $this->file,
                    'line' => $class->getAttribute('startLine'),
                    'name' => $fullClassName,
                    'docblock' => $this->getDocblock($class, $uses),
                ];

                foreach ($statement->stmts as $method) {
                    if (!($method instanceof ClassMethod)) {
                        continue;
                    }

                    $fullMethodName = $fullClassName . '::' . (string)$method->name;

                    $type = $method->returnType;

                    if (!is_null($type)) {
                        $type = (string)$type;
                    }

                    if (isset($uses[$type])) {
                        $type = $uses[$type];
                    }

                    $type = substr($type, 0, 1) == '/' ? substr($type, 1) : $type;


                    $thisMethod = [
                        'file' => $this->file,
                        'class' => $fullClassName,
                        'name' => $fullMethodName,
                        'line' => $method->getAttribute('startLine'),
                        'return' => $type,
                        'params' => [],
                        'docblock' => $this->getDocblock($method, $uses),
                    ];

                    foreach ($method->params as $param) {
                        $type = $param->type;

                        if (!is_null($type)) {
                            $type = (string)$type;
                        }

                        if (isset($uses[$type])) {
                            $type = $uses[$type];
                        }

                        $type = substr($type, 0, 1) == '/' ? substr($type, 1) : $type;

                        $thisMethod['params']['$'.$param->name] = $type;
                    }

                    $this->methods[$fullMethodName] = $thisMethod;
                }
            }
        }
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
        $parser = new DocBlockParser($text);

        $rtn = ['params' => [], 'return' => null];

        if (isset($parser->tags['param'])) {
            foreach ($parser->tags['param'] as $param) {
                $type = $param['type'];

                if (!is_null($type)) {
                    $type = (string)$type;
                }

                if (isset($uses[$type])) {
                    $type = $uses[$type];
                }

                $type = substr($type, 0, 1) == '/' ? substr($type, 1) : $type;

                $rtn['params'][$param['var']] = $type;
            }
        }

        if (isset($parser->tags['return'])) {
            $return = array_shift($parser->tags['return']);

            $type = $return['type'];

            if (!is_null($type)) {
                $type = (string)$type;
            }

            if (isset($uses[$type])) {
                $type = $uses[$type];
            }

            $type = substr($type, 0, 1) == '/' ? substr($type, 1) : $type;

            $rtn['return'] = $type;
        }

        return $rtn;
    }
}
