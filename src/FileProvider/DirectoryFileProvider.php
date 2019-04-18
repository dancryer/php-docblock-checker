<?php

namespace PhpDocBlockChecker\FileProvider;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DirectoryFileProvider implements FileProviderInterface
{
    private $excludes;
    /**
     * @var string
     */
    private $directory;

    /**
     * DirectoryFileProvider constructor.
     * @param string $directory
     * @param array $excludes
     */
    public function __construct($directory, array $excludes)
    {
        $this->directory = $directory;
        $this->excludes = $excludes;
    }

    /**
     * @return \Iterator
     */
    public function getFileIterator()
    {
        $directory = new RecursiveDirectoryIterator($this->directory, FilesystemIterator::SKIP_DOTS);

        $iterator = new RecursiveIteratorIterator($directory);
        return new FileExclusionFilter($iterator, $this->directory, $this->excludes);
    }
}
