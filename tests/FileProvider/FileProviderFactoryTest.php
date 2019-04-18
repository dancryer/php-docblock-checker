<?php

namespace PhpDocBlockChecker\FileProvider;

use PhpDocBlockChecker\Config\Config;

class FileProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFileProvider()
    {
        $provider = FileProviderFactory::getFileProvider(Config::fromArray([]));
        $this->assertInstanceOf(FileProviderInterface::class, $provider);
    }
}
