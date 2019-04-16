<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\Status\FileStatus;

/**
 * Class Check
 * @package PhpDocBlockChecker\Check
 */
abstract class Check implements CheckInterface
{
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var FileStatus
     */
    protected $fileStatus;

    /**
     * Check constructor.
     * @param Config $config
     * @param FileStatus $fileStatus
     */
    public function __construct(Config $config, FileStatus $fileStatus)
    {
        $this->config = $config;
        $this->fileStatus = $fileStatus;
    }
}
