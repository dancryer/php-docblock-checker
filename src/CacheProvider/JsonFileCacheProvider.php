<?php declare(strict_types=1);

namespace PhpDocBlockChecker\CacheProvider;

use PhpDocBlockChecker\FileInfo;
use RuntimeException;

/**
 * Class JsonFileCacheProvider
 * @package PhpDocBlockChecker\CacheProvider
 */
class JsonFileCacheProvider implements CacheProviderInterface
{
    /**
     * @var mixed[]
     */
    private $cache = [];
    /**
     * @var string
     */
    private $cacheFile;

    /**
     * CacheProvider constructor.
     * @param string $cacheFile
     */
    public function __construct(string $cacheFile)
    {
        // Load cache from file if set:
        if (file_exists($cacheFile)) {
            $contents = file_get_contents($cacheFile);
            if ($contents !== false) {
                $this->cache = json_decode($contents, true);
            }
        }
        $this->cacheFile = $cacheFile;
    }

    public function __destruct()
    {
        // Write to cache file:
        if ($this->cacheFile === null) {
            return;
        }
        file_put_contents($this->cacheFile, json_encode($this->cache));
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function exists(string $fileName): bool
    {
        return isset($this->cache[$fileName]);
    }

    /**
     * @param string $fileName
     * @return FileInfo
     */
    public function get(string $fileName): FileInfo
    {
        if ($this->exists($fileName)) {
            return FileInfo::fromArray($this->cache[$fileName]);
        }
        throw new RuntimeException(sprintf('Filename "%s" does not exist', $fileName));
    }

    /**
     * @param FileInfo $fileInfo
     */
    public function set(FileInfo $fileInfo): void
    {
        $this->cache[$fileInfo->getFileName()] = $fileInfo;
    }
}
