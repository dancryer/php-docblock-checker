<?php

namespace PhpDocBlockChecker\FileProvider;

use PHPUnit\Framework\TestCase;

class StdinFileProviderTest extends TestCase
{
    public function testGetFileIterator(): void
    {
        $handle = fopen('php://memory', 'wb+');
        fwrite($handle, "test\ntest.php\ntest2.php\ntest3/test.php");
        rewind($handle);

        $provider = new StdinFileProvider($handle, ['test2.php', 'test3/*']);
        $iterator = $provider->getFileIterator();

        $this->assertInstanceOf(\ArrayIterator::class, $iterator);
        $this->assertCount(1, $iterator);
        foreach ($iterator as $value) {
            $this->assertInstanceOf(\SplFileInfo::class, $value);
            $this->assertEquals('test.php', $value->getPathname());
        }
    }
}
