<?php

namespace PhpDocBlockChecker\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpParser\ParserFactory;

/**
 * @requires PHP 7.0
 */
class FileParserTest extends \PHPUnit_Framework_TestCase
{
    protected $filePath = __DIR__ . '/TestClassPhp7.php';
    protected $fileInfo;

    protected function setUp()
    {
        $fileParser = new FileParser(
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new DocblockParser()
        );

        $this->fileInfo = $fileParser->parseFile($this->filePath);
    }

    public function testFileLoaded()
    {
        $this->assertEquals($this->filePath, $this->fileInfo->getFileName());
    }

    /**
     * @requires PHP 7.0
     */
    public function testWithReturnHint()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withReturnHint'];
        $this->assertTrue($method['has_return']);
        $this->assertEquals('string', $method['return']);
        $this->assertEquals('string', $method['docblock']['return']);
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withNullableReturnHint'];
        $this->assertTrue($method['has_return']);
        $this->assertEquals(['null', 'string'], $method['return']);
        $this->assertEquals(['null', 'string'], $method['docblock']['return']);
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithMixedOrderNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withMixedOrderNullableReturnHint'];
        $this->assertTrue($method['has_return']);
        $this->assertEquals(['null', 'string'], $method['return']);
        $this->assertEquals(['null', 'string'], $method['docblock']['return']);
    }
}
