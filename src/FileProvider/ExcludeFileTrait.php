<?php


namespace PhpDocBlockChecker\FileProvider;

trait ExcludeFileTrait
{
    /**
     * @var array
     */
    protected $excludes = [];

    /**
     * @param string $baseDirectory
     * @param \SplFileInfo $file
     * @return bool
     */
    protected function isFileExcluded($baseDirectory, \SplFileInfo $file)
    {
        if ($file->getExtension() !== 'php') {
            return true;
        }

        $filePath = str_replace($baseDirectory, '', $file->getPathname());

        if (in_array($filePath, $this->excludes, true)) {
            return true;
        }

        foreach ($this->excludes as $pattern) {
            if (fnmatch($pattern, $filePath)) {
                return true;
            }
        }

        return false;
    }
}
