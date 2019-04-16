<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\FileStatus;
use PhpDocBlockChecker\Status\StatusType\Passed\Passed;

class Checker
{
    /**
     * @var Config
     */
    private $config;

    private $checks = [
        ClassCheck::class,
        MethodCheck::class,
        ParamCheck::class,
        ReturnCheck::class
    ];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param FileInfo $fileInfo
     * @return FileStatus
     */
    public function check(FileInfo $fileInfo)
    {
        $fileStatus = new FileStatus();
        foreach ($this->checks as $check) {
            /** @var CheckInterface $check */
            $check = new $check($this->config, $fileStatus);
            if ($check->enabled()) {
                $check->check($fileInfo);
            }
        }

        if (!$fileStatus->hasErrors()) {
            $fileStatus->add(new Passed($fileInfo->getFileName()));
        }

        return $fileStatus;
    }
}
