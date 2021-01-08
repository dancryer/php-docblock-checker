<?php

namespace PhpDocBlockChecker\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpParser\ParserFactory;

class FileParserTest extends \PHPUnit_Framework_TestCase
{
    protected $filePath = __DIR__ . '/TestClass.php';
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

    public function testClassLoaded()
    {
        $classes = $this->fileInfo->getClasses();
        $this->assertCount(1, $classes);

        $class = $classes['PhpDocBlockChecker\FileParser\TestClass'];
        $this->assertEquals('PhpDocBlockChecker\FileParser\TestClass', $class['name']);
        $this->assertEquals(null, $class['docblock']);
    }

    public function testWithNoReturn()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::emptyMethod'];
        $this->assertFalse($method['has_return']);
        $this->assertEquals(null, $method['return']);
    }

    public function testWithNoParams()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::emptyMethod'];
        $this->assertEmpty($method['params']);
    }

    public function testWithParams()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withParams'];
        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null,], $method['params']);
    }

    public function testWithReturn()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withReturn'];
        $this->assertTrue($method['has_return']);
        $this->assertEquals(null, $method['return']);
    }
}
