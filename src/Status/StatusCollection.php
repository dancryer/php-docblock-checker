<?php

namespace PhpDocBlockChecker\Status;

use PhpDocBlockChecker\Status\StatusType\Error\Error;
use PhpDocBlockChecker\Status\StatusType\Info\Info;
use PhpDocBlockChecker\Status\StatusType\Warning\Warning;

/**
 * Class StatusCollection
 * @package PhpDocBlockChecker\Status
 */
class StatusCollection
{
    /**
     * @var Error[]
     */
    private $errors = [];
    /**
     * @var Warning[]
     */
    private $warnings = [];
    /**
     * @var Info[]
     */
    private $infos = [];
    private $passed = [];

    /**
     * @param FileStatus $fileStatus
     */
    public function addFileStatus(FileStatus $fileStatus)
    {
        foreach ($fileStatus->getErrors() as $error) {
            $this->errors[] = $error;
        }
        foreach ($fileStatus->getWarnings() as $warning) {
            $this->warnings[] = $warning;
        }
        foreach ($fileStatus->getInfos() as $info) {
            $this->infos[] = $info;
        }

        foreach ($fileStatus->getPassed() as $passed) {
            $this->passed[] = $passed;
        }
    }

    /**
     * @return int
     */
    public function getTotalErrors()
    {
        return count($this->errors);
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->getTotalErrors() > 0;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function getTotalWarnings()
    {
        return count($this->warnings);
    }

    /**
     * @return bool
     */
    public function hasWarnings()
    {
        return $this->getTotalWarnings() > 0;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @return int
     */
    public function getTotalInfos()
    {
        return count($this->infos);
    }

    /**
     * @return bool
     */
    public function hasInfos()
    {
        return $this->getTotalInfos() > 0;
    }

    /**
     * @return Info[]
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @return int|void
     */
    public function getTotalPassed()
    {
        return count($this->passed);
    }

    /**
     * @return bool
     */
    public function hasPassed()
    {
        return $this->getTotalPassed() > 0;
    }

    /**
     * @return array
     */
    public function getPassed()
    {
        return $this->passed;
    }
}
