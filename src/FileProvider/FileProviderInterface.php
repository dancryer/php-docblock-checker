<?php

namespace PhpDocBlockChecker\FileProvider;

interface FileProviderInterface
{
    /**
     * @return string[]
     */
    public function getFiles();
}
