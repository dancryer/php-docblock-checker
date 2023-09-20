<?php

namespace PhpDocBlockChecker\FileProvider;

use Iterator;

class FileExclusionFilter extends \FilterIterator
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
     * @param array $excludes
     */
    public function __construct(Iterator $iterator, $baseDirectory, array $excludes)
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
