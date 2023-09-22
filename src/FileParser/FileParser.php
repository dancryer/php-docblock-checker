<?php

namespace PhpDocBlockChecker\FileParser;

use PhpDocBlockChecker\Code\ClassDocBlock;
use PhpDocBlockChecker\Code\DocBlockInterface;
use PhpDocBlockChecker\Code\Method;
use PhpDocBlockChecker\Code\MethodDocBlock;
use PhpDocBlockChecker\Code\Param as CodeParam;
use PhpDocBlockChecker\Code\ReturnType;
use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\DocblockParser\ReturnTag;
use PhpDocBlockChecker\FileInfo;
use PhpParser\Comment\Doc;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UnionType;
use PhpParser\NodeAbstract;
use PhpParser\Parser;

/**
 * Uses Nikic/PhpParser to parse PHP files and find relevant information for the checker.
 * @package PhpDocBlockChecker
 */
class FileParser
{
    /**
     * @var DocblockParser
     */
    private $docblockParser;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * Load and parse a PHP file.
     * @param Parser $parser
     * @param DocblockParser $docblockParser
     */
    public function __construct(Parser $parser, DocblockParser $docblockParser)
    {
        $this->parser = $parser;
        $this->docblockParser = $docblockParser;
    }

    /**
     * @param string $file
     * @return FileInfo
     */
    public function parseFile($file)
    {
        $contents = file_get_contents($file);
        if ($contents === false) {
            throw new \RuntimeException(sprintf('Unable to read file "%s"', $file));
        }
        $stmts = $this->parser->parse($contents);

        if ($stmts === null) {
            return new FileInfo($file, [], [], filemtime($file));
        }

        $result = $this->processStatements($file, $stmts);
        return new FileInfo(
            $file,
            $result['classes'],
            $result['methods'],
            filemtime($file)
        );
    }

    /**
     * Looks for class definitions, and then within them method definitions, docblocks, etc.
     * @param string $file
     * @param array $statements
     * @param string $prefix
     * @return mixed
     */
    protected function processStatements($file, array $statements, $prefix = '')
    {
        $uses = [];
        $methods = [];
        $classes = [];

        foreach ($statements as $statement) {
            if ($statement instanceof Namespace_) {
                return $this->processStatements($file, $statement->stmts, (string)$statement->name);
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

                $classDocBlock = ClassDocBlock::factory()
                    ->setNamespace($prefix ?: null)
                    ->setClass($class->name)
                    ->setUses($uses);

                $classes[$fullClassName] = [
                    'file' => $file,
                    'line' => $class->getAttribute('startLine'),
                    'name' => $fullClassName,
                    'docblock' => $this->getDocblock($class, $classDocBlock),
                ];

                foreach ($statement->stmts as $method) {
                    if (!$method instanceof ClassMethod) {
                        continue;
                    }

                    $methodObject = Method::factory()
                        ->setNamespace($prefix ?: null)
                        ->setClass($class->name)
                        ->setUses($uses)
                        ->setLine($method->getAttribute('startLine'))
                        ->setName($method->name)
                        ->setHasReturn(isset($method->stmts) ? $this->statementsContainReturn($method->stmts) : false)
                        ;

                    $methodDocBlock = MethodDocBlock::factory()->setFromAbstract($methodObject);
                    $methodObject->setDocBlock($this->getDocblock($method, $methodDocBlock));

                    $fullMethodName = $fullClassName . '::' . $method->name;

                    $type = $method->returnType;

                    $returnType = ReturnType::factory()
                        ->setFromAbstract($methodObject);

                    if ($type instanceof NullableType) {
                        $returnType
                            ->addType($type->type->toString())
                            ->setNullable(true);
                    } elseif ($type instanceof UnionType) {
                        foreach ($type->types as $toAdd) {
                            $returnType->addType($toAdd->toString());
                        }
                    } elseif ($type instanceof NodeAbstract) {
                        $returnType->addType($type->toString());
                    } else {
                        $returnType = null;
                    }

                    $methodObject->setReturnType($returnType);

                    /** @var Param $param */
                    foreach ($method->params as $param) {
                        $type = $param->type;

                        $paramType = CodeParam::factory()
                            ->setFromAbstract($methodObject);

                        if ($type instanceof NullableType) {
                            $paramType
                                ->addType($type->type->toString())
                                ->setNullable(true);
                        } elseif ($type instanceof UnionType) {
                            foreach ($type->types as $toAdd) {
                                $paramType->addType($toAdd->toString());
                            }
                        } elseif ($type instanceof NodeAbstract) {
                            $paramType->addType($type->toString());
                        }


                        if (property_exists($param, 'default') &&
                            $param->default instanceof Expr &&
                            property_exists($param->default, 'name') &&
                            property_exists($param->default->name, 'parts') &&
                            $type !== null &&
                            'null' === $param->default->name->parts[0]
                        ) {
                            $paramType->setNullable(true);
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
                        $paramType->setName($name);

                        if (property_exists($param, 'variadic') && $param->variadic) {
                            $name .= ',...';
                            $paramType->setVariadic(true);
                        }

                        $methodObject->addParam($paramType);
                    }

                    $methods[$fullMethodName] = $methodObject;
                }
            }
        }

        return ['methods' => $methods, 'classes' => $classes];
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
     * @param \PhpDocBlockChecker\Code\DocBlockInterface $docBlock
     * @return \PhpDocBlockChecker\Code\DocBlockInterface|null
     */
    protected function getDocblock(Stmt $stmt, DocBlockInterface $docBlock)
    {
        $comments = $stmt->getAttribute('comments');

        if (is_array($comments)) {
            foreach ($comments as $comment) {
                if ($comment instanceof Doc) {
                    return $this->processDocblock($comment->getText(), $docBlock);
                }
            }
        }

        return null;
    }

    /**
     * @param string $text
     * @param \PhpDocBlockChecker\Code\DocBlockInterface $docBlock
     * @return \PhpDocBlockChecker\Code\DocBlockInterface
     */
    protected function processDocblock($text, DocBlockInterface $docBlock): DocBlockInterface
    {
        $tagCollection = $this->docblockParser->parseComment($text);

        if ($tagCollection->hasTag('inheritdoc') || $tagCollection->hasTag('inheritDoc')) {
            $docBlock->setInherited(true);
            return $docBlock;
        }

        if ($docBlock instanceof MethodDocBlock) {
            if ($tagCollection->hasTag('param')) {
                foreach ($tagCollection->getParamTags() as $paramTag) {
                    $param = CodeParam::factory()
                        ->setFromAbstract($docBlock)
                        ->addTypesFromString($paramTag->getType())
                        ->setName($paramTag->getVar())
                        ->setVariadic($paramTag->isVariadic());

                    $docBlock->addParam($param);
                }
            }

            if ($tagCollection->hasTag('return')) {
                $return = $tagCollection->getReturnTags();
                $return = array_shift($return);

                if ($return instanceof ReturnTag) {
                    $returnType = ReturnType::factory()
                        ->setFromAbstract($docBlock)
                        ->addTypesFromString($return->getType());

                    $docBlock->setReturnType($returnType);
                }
            }
        }

        return $docBlock;
    }
}
