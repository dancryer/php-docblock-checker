<?php

namespace PhpDocBlockChecker\FileProvider;

use PhpDocBlockChecker\Config\Config;
use RuntimeException;

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
            if ($handle === false) {
                throw new RuntimeException('Unable to open stdin for reading');
            }
            return new StdinFileProvider($handle, $config->getExclude());
        }
        return new DirectoryFileProvider($config->getDirectory(), $config->getExclude());
    }
}
