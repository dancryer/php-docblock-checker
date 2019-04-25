<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;

/**
 * Interface CheckInterface
 * @package PhpDocBlockChecker\Check
 */
interface CheckInterface
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file): void;

    /**
     * @return bool
     */
    public function enabled(): bool;
}
