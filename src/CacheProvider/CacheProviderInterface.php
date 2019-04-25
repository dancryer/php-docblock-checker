<?php declare(strict_types=1);

namespace PhpDocBlockChecker\CacheProvider;

use PhpDocBlockChecker\FileInfo;

/**
 * Class JsonFileCacheProvider
 * @package PhpDocBlockChecker\CacheProvider
 */
interface CacheProviderInterface
{
    /**
     * @param string $fileName
     * @return bool
     */
    public function exists(string $fileName): bool;

    /**
     * @param string $fileName
     * @return FileInfo
     */
    public function get(string $fileName): FileInfo;

    /**
     * @param FileInfo $fileInfo
     */
    public function set(FileInfo $fileInfo): void;
}
