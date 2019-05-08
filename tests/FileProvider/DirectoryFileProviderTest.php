<?php

namespace PhpDocBlockChecker\FileProvider;


use PHPUnit\Framework\TestCase;

class DirectoryFileProviderTest extends TestCase
{

    public function testGetFileIterator()
    {
        $provider = new DirectoryFileProvider(__DIR__, []);

        $iterator = $provider->getFileIterator();

        $this->assertInstanceOf(\Iterator::class, $iterator);
        $arr = iterator_to_array($iterator);

        $filePath = __DIR__ . '/DirectoryFileProviderTest.php';
        /** @var \SplFileInfo $file */
        $file = $arr[$filePath];
        $this->assertInstanceOf(\SplFileInfo::class, $file);
        $this->assertEquals($filePath, $file->getPathname());
    }
}
