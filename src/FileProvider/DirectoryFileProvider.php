<?php declare(strict_types=1);

namespace PhpDocBlockChecker\FileProvider;

use FilesystemIterator;
use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Traversable;

/**
 * Class DirectoryFileProvider
 * @package PhpDocBlockChecker\FileProvider
 */
class DirectoryFileProvider implements FileProviderInterface
{
    /**
     * @var string[]
     */
    private $excludes;
    /**
     * @var string
     */
    private $directory;

    /**
     * DirectoryFileProvider constructor.
     * @param string $directory
     * @param string[] $excludes
     */
    public function __construct(string $directory, array $excludes)
    {
        $this->directory = $directory;
        $this->excludes = $excludes;
    }

    /**
     * @return Iterator
     */
    public function getFileIterator(): Traversable
    {
        $directory = new RecursiveDirectoryIterator($this->directory, FilesystemIterator::SKIP_DOTS);

        $iterator = new RecursiveIteratorIterator($directory);
        return new FileExclusionFilter($iterator, $this->directory, $this->excludes);
    }
}
