<?php

namespace PhpDocBlockChecker;

class FileInfoCacheProvider
{
    /**
     * @var array
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
    public function __construct($cacheFile)
    {
        // Load cache from file if set:
        if (!empty($cacheFile) && file_exists($cacheFile)) {
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
        if (!empty($this->cacheFile)) {
            @file_put_contents($this->cacheFile, json_encode($this->cache));
        }
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function exists($fileName)
    {
        return isset($this->cache[$fileName]);
    }

    /**
     * @param string $fileName
     * @return FileInfo
     */
    public function get($fileName)
    {
        if ($this->exists($fileName)) {
            return FileInfo::fromArray($this->cache[$fileName]);
        }
        throw new \RuntimeException(sprintf('Filename "%s" does not exist', $fileName));
    }

    /**
     * @param FileInfo $fileInfo
     */
    public function set($fileInfo)
    {
        $this->cache[$fileInfo->getFileName()] = $fileInfo;
    }
}
