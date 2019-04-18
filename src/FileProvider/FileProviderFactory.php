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
            $handle = fopen('php://stdin', 'rb');
            return new StdinFileProvider($handle, $config->getExclude());
        }
        return new DirectoryFileProvider($config->getDirectory(), $config->getExclude());
    }
}
