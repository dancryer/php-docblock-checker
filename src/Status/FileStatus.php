<?php declare(strict_types=1);

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
     * @var Passed[]
     */
    private $passed = [];

    /**
     * @param StatusType $status
     */
    public function add(StatusType $status): void
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
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @return bool
     */
    public function hasWarnings(): bool
    {
        return count($this->warnings) > 0;
    }

    /**
     * @return Info[]
     */
    public function getInfos(): array
    {
        return $this->infos;
    }

    /**
     * @return bool
     */
    public function hasInfos(): bool
    {
        return count($this->infos) > 0;
    }

    /**
     * @return Passed[]
     */
    public function getPassed(): array
    {
        return $this->passed;
    }

    /**
     * @return bool
     */
    public function hasPassed(): bool
    {
        return count($this->passed) > 0;
    }
}
