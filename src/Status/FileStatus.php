<?php

namespace PhpDocBlockChecker\Status;

use PhpDocBlockChecker\Status\StatusType\Error\Error;
use PhpDocBlockChecker\Status\StatusType\Info\Info;
use PhpDocBlockChecker\Status\StatusType\Passed\Passed;
use PhpDocBlockChecker\Status\StatusType\StatusType;
use PhpDocBlockChecker\Status\StatusType\Warning\Warning;

/**
 * Class FileStatus
 * @package PhpDocBlockChecker\Status
 */
class FileStatus
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
    /**
     * @var array
     */
    private $passed = [];

    /**
     * @param StatusType $status
     */
    public function add(StatusType $status)
    {
        if ($status instanceof Error) {
            $this->errors[] = $status;
        }

        if ($status instanceof Warning) {
            $this->warnings[] = $status;
        }

        if ($status instanceof Info) {
            $this->infos[] = $status;
        }

        if ($status instanceof Passed) {
            $this->passed[] = $status;
        }
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @return bool
     */
    public function hasWarnings()
    {
        return count($this->warnings) > 0;
    }

    /**
     * @return Info[]
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @return bool
     */
    public function hasInfos()
    {
        return count($this->infos) > 0;
    }

    /**
     * @return array
     */
    public function getPassed()
    {
        return $this->passed;
    }

    /**
     * @return bool
     */
    public function hasPassed()
    {
        return count($this->passed) > 0;
    }
}
