<?php

namespace PhpDocBlockChecker\FileProvider;

use PhpDocBlockChecker\Config\Config;

class FileProviderFactory
{
    /**
     * @param Config $config
     * @return FileProviderInterface
     */
    public static function getFileProvider(Config $config)
    {
        if ($config->isFromStdin()) {
            return new StdinFileProvider($config->getExclude());
        }
        return new DirectoryFileProvider($config->getDirectory(), $config->getExclude());
    }
}
