<?php declare(strict_types=1);

namespace PhpDocBlockChecker\FileProvider;

use SplFileInfo;

/**
 * Trait ExcludeFileTrait
 * @package PhpDocBlockChecker\FileProvider
 */
trait ExcludeFileTrait
{
    /**
     * @var string[]
     */
    protected $excludes = [];

    /**
     * @param string $baseDirectory
     * @param SplFileInfo $file
     * @return bool
     */
    protected function isFileExcluded(string $baseDirectory, SplFileInfo $file): bool
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
