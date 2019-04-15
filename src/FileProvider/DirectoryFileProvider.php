<?php

namespace PhpDocBlockChecker\FileProvider;

use DirectoryIterator;

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
     * @param string $path
     * @return string[]
     */
    public function getFiles()
    {
        $directory = new RecursiveDirectoryIterator($this->directory);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($regex as $x) {

        }

        $dir = new DirectoryIterator($this->directory . $path);
        $worklist = [];

        foreach ($dir as $item) {
            if ($item->isDot()) {
                continue;
            }

            $itemPath = $path . $item->getFilename();

            if ($this->isFileExcluded($itemPath)) {
                continue;
            }

            if ($item->isFile() && $item->getExtension() === 'php') {
                $worklist[] = $itemPath;
            }

            if ($item->isDir()) {
                $worklist = array_merge($worklist, $this->getFiles($itemPath . '/', $worklist));
            }
        }

        return $worklist
    }
}
