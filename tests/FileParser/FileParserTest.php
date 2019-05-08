<?php

namespace PhpDocBlockChecker\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

class FileParserTest extends TestCase
{

    public function testParseFile()
    {
        $fileParser = new FileParser(
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new DocblockParser()
        );

        $filePath = __DIR__ . '/TestClass.php';

        $fileInfo = $fileParser->parseFile($filePath);

        $this->assertEquals($filePath, $fileInfo->getFileName());
        $class = $fileInfo->getClasses()['PhpDocBlockChecker\FileParser\TestClass'];

        $this->assertEquals('PhpDocBlockChecker\FileParser\TestClass', $class['name']);
        $this->assertEquals(null, $class['docblock']);

        $methodFoo = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::foo'];

        $this->assertFalse($methodFoo['has_return']);
        $this->assertEmpty($methodFoo['params']);

        $methodBar = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::bar'];
        $this->assertFalse($methodBar['has_return']);
        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null,], $methodBar['params']);

        $methodBaz = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::baz'];
        $this->assertTrue($methodBaz['has_return']);
        $this->assertEmpty($methodBaz['params']);
    }
}
