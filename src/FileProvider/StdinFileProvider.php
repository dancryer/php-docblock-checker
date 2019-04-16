<?php

namespace PhpDocBlockChecker\FileProvider;

class StdinFileProvider extends FileProvider
{
    /**
     * StdinFileProvider constructor.
     * @param array $excludes
     */
    public function __construct(array $excludes)
    {
        $this->excludes = $excludes;
    }

    /**
     * @return string[]
     */
    public function getFiles()
    {
        $files = file('php://stdin');

        if (empty($files) || !is_array($files)) {
            return [];
        }

        $worklist = [];

        /** @var string $file */
        foreach ($files as $file) {
            $file = trim($file);

            if (!is_file($file)) {
                continue;
            }

            if ($this->isFileExcluded($file)) {
                continue;
            }

            if (substr($file, -3) === 'php') {
                $worklist[] = $file;
            }
        }

        return $worklist;
    }
}
