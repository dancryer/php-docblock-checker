<?php

namespace PhpDocBlockChecker\FileProvider;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class DirectoryFileProvider extends FileProvider
{
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
     * @return string[]
     */
    public function getFiles()
    {
        $directory = new RecursiveDirectoryIterator($this->directory);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        $worklist = [];

        foreach ($regex as $match) {
            if (!isset($match[0])) {
                continue;
            }
            $file = $match[0];

            if (!$this->isFileExcluded(str_replace($this->directory, '', $file))) {
                $worklist[] = $file;
            }
        }

        return $worklist;
    }
}
