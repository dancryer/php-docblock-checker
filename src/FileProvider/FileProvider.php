<?php

namespace PhpDocBlockChecker\FileProvider;

abstract class FileProvider implements FileProviderInterface
{
    /**
     * @var array
     */
    protected $excludes;

    /**
     * @param string $file
     * @return bool
     */
    protected function isFileExcluded($file)
    {
        if (in_array($file, $this->excludes, true)) {
            return true;
        }

        foreach ($this->excludes as $pattern) {
            if (fnmatch($pattern, $file)) {
                return true;
            }
        }

        return false;
    }
}
