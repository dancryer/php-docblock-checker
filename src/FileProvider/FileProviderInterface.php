<?php declare(strict_types=1);

namespace PhpDocBlockChecker\FileProvider;

use Traversable;

/**
 * Interface FileProviderInterface
 * @package PhpDocBlockChecker\FileProvider
 */
interface FileProviderInterface
{
    /**
     * @return Traversable
     */
    public function getFileIterator(): Traversable;
}
