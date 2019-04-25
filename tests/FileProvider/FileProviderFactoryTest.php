<?php

namespace PhpDocBlockChecker\FileProvider;

use PhpDocBlockChecker\Config\Config;
use PHPUnit\Framework\TestCase;

class FileProviderFactoryTest extends TestCase
{
    public function testGetFileProvider()
    {
        $provider = FileProviderFactory::getFileProvider(Config::fromArray([]));
        $this->assertInstanceOf(FileProviderInterface::class, $provider);
    }
}
