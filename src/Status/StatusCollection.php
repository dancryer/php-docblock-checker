<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status;

use PhpDocBlockChecker\Status\StatusType\Error\Error;
use PhpDocBlockChecker\Status\StatusType\Info\Info;
use PhpDocBlockChecker\Status\StatusType\Passed\Passed;
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
    /**
     * @var Passed[]
     */
    private $passed = [];

    /**
     * @param FileStatus $fileStatus
     */
    public function addFileStatus(FileStatus $fileStatus): void
    {
        $this->errors = array_merge($this->errors, $fileStatus->getErrors());
        $this->warnings = array_merge($this->warnings, $fileStatus->getWarnings());
        $this->infos = array_merge($this->infos, $fileStatus->getInfos());
        $this->passed = array_merge($this->passed, $fileStatus->getPassed());
    }

    /**
     * @return int
     */
    public function getTotalErrors(): int
    {
        return count($this->errors);
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->getTotalErrors() > 0;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function getTotalWarnings(): int
    {
        return count($this->warnings);
    }

    /**
     * @return bool
     */
    public function hasWarnings(): bool
    {
        return $this->getTotalWarnings() > 0;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @return int
     */
    public function getTotalInfos(): int
    {
        return count($this->infos);
    }

    /**
     * @return bool
     */
    public function hasInfos(): bool
    {
        return $this->getTotalInfos() > 0;
    }

    /**
     * @return Info[]
     */
    public function getInfos(): array
    {
        return $this->infos;
    }

    /**
     * @return int
     */
    public function getTotalPassed(): int
    {
        return count($this->passed);
    }

    /**
     * @return bool
     */
    public function hasPassed(): bool
    {
        return $this->getTotalPassed() > 0;
    }

    /**
     * @return Passed[]
     */
    public function getPassed(): array
    {
        return $this->passed;
    }
}
