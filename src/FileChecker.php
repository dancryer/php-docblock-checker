<?php declare(strict_types=1);

namespace PhpDocBlockChecker;

use PhpDocBlockChecker\CacheProvider\CacheProviderInterface;
use PhpDocBlockChecker\Check\Checker;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\Status\FileStatus;

/**
 * Class FileChecker
 * @package PhpDocBlockChecker
 */
class FileChecker
{
    /**
     * @var CacheProviderInterface
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
     * @param CacheProviderInterface $cache
     * @param FileParser $fileParser
     * @param Checker $checker
     */
    public function __construct(CacheProviderInterface $cache, FileParser $fileParser, Checker $checker)
    {
        $this->cache = $cache;
        $this->fileParser = $fileParser;
        $this->checker = $checker;
    }

    /**
     * @param string $fileName
     * @return FileStatus
     */
    public function checkFile(string $fileName): FileStatus
    {
        $file = $this->getFileDetails($fileName);

        return $this->checker->check($file);
    }

    /**
     * @param string $fileName
     * @return FileInfo
     */
    private function getFileDetails(string $fileName): FileInfo
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
