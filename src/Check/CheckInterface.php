<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;

interface CheckInterface
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file);

    /**
     * @return bool
     */
    public function enabled();
}
