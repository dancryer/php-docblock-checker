<?php declare(strict_types=1);

namespace PhpDocBlockChecker\CacheProvider;

use PhpDocBlockChecker\FileInfo;
use RuntimeException;

/**
 * Class NullCacheProvider
 * @package PhpDocBlockChecker\CacheProvider
 */
class NullCacheProvider implements CacheProviderInterface
{
    /**
     * @param string $fileName
     * @return bool
     */
    public function exists(string $fileName): bool
    {
        return false;
    }

    /**
     * @param string $fileName
     * @return FileInfo
     */
    public function get(string $fileName): FileInfo
    {
        throw new RuntimeException('Cannot get items from a NullCache');
    }

    /**
     * @param FileInfo $fileInfo
     */
    public function set(FileInfo $fileInfo): void
    {
        //noop
    }
}
