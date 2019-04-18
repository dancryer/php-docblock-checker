<?php

namespace PhpDocBlockChecker\FileProvider;

interface FileProviderInterface
{
    /**
     * @return \Traversable
     */
    public function getFileIterator();
}
