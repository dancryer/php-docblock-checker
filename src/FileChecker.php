<?php

namespace PhpDocBlockChecker;

use PhpDocBlockChecker\Check\Checker;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\Status\FileStatus;

class FileChecker
{
    /**
     * @var FileInfoCacheProvider
     */
    private $cache;
    /**
     * @var Checker
     */
    private $checker;
    /**
     * @var FileParser
     */
    private $fileParser;

    /**
     * FileChecker constructor.
     * @param FileInfoCacheProvider $cache
     * @param FileParser $fileParser
     * @param Checker $checker
     */
    public function __construct(FileInfoCacheProvider $cache, FileParser $fileParser, Checker $checker)
    {
        $this->cache = $cache;
        $this->fileParser = $fileParser;
        $this->checker = $checker;
    }

    /**
     * @param string $fileName
     * @return FileStatus
     */
    public function checkFile($fileName)
    {
        $file = $this->getFileDetails($fileName);

        return $this->checker->check($file);
    }

    /**
     * @param string $fileName
     * @return FileInfo
     */
    private function getFileDetails($fileName)
    {
        if ($this->cache->exists($fileName)) {
            $cachedFile = $this->cache->get($fileName);
            if (filemtime($fileName) <= $cachedFile->getMtime()) {
                return $cachedFile;
            }
        }

        $fileInfo = $this->fileParser->parseFile($fileName);
        $this->cache->set($fileInfo);

        return $fileInfo;
    }
}
