<?php

namespace PhpDocBlockChecker;

use PhpDocBlockChecker\Check\Checker;
use PhpDocBlockChecker\Status\FileStatus;

class FileChecker
{
    /**
     * @var FileInfoCacheProvider
     */
    private $cache;
    private $parser;

    /**
     * @var Checker
     */
    private $checker;

    public function __construct(FileInfoCacheProvider $cache, $parser, Checker $checker)
    {
        $this->cache = $cache;
        $this->parser = $parser;
        $this->checker = $checker;
    }

    /**
     * @param string $fileName
     * @return FileStatus
     */
    public function checkFile($fileName)
    {
        $file = $this->getFile($fileName);

        return $this->checker->check($file);
    }

    /**
     * @param $fileName
     * @return FileInfo
     */
    private function getFile($fileName)
    {
        if ($this->cache->exists($fileName)) {
            $cachedFile = $this->cache->get($fileName);
            if (filemtime($fileName) <= $cachedFile->getMtime()) {
                return $cachedFile;
            }
        }

        $processor = new FileProcessor($fileName, $this->parser);
        $fileInfo = new FileInfo(
            $fileName,
            $processor->getClasses(),
            $processor->getMethods(),
            filemtime($fileName)
        );

        unset($processor);

        $this->cache->set($fileInfo);

        return $fileInfo;
    }
}
