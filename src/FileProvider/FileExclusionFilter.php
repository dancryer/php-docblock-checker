<?php declare(strict_types=1);

namespace PhpDocBlockChecker\FileProvider;

use FilterIterator;
use Iterator;

/**
 * Class FileExclusionFilter
 * @package PhpDocBlockChecker\FileProvider
 */
class FileExclusionFilter extends FilterIterator
{

    use ExcludeFileTrait;

    /**
     * @var string
     */
    private $baseDirectory;

    /**
     * FileExclusionFilter constructor.
     * @param Iterator $iterator
     * @param string $baseDirectory
     * @param string[] $excludes
     */
    public function __construct(Iterator $iterator, string $baseDirectory, array $excludes)
    {
        parent::__construct($iterator);
        $this->baseDirectory = $baseDirectory;
        $this->excludes = $excludes;
    }

    /**
     * Check whether the current element of the iterator is acceptable
     * @link https://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     * @since 5.1.0
     */
    public function accept(): bool
    {
        $file = $this->getInnerIterator()->current();
        return !$this->isFileExcluded($this->baseDirectory, $file);
    }
}
