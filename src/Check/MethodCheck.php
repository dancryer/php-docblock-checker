<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Error\MethodError;
use PhpDocBlockChecker\Status\StatusType\Info\MethodInfo;

/**
 * Class MethodCheck
 * @package PhpDocBlockChecker\Check
 */
class MethodCheck extends Check
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file): void
    {
        foreach ($file->getMethods() as $name => $method) {
            $treatAsError = true;
            if (false === $method['has_return'] &&
                $this->config->isOnlySignatures() &&
                ($method['params'] === null || 0 === count($method['params']))) {
                $treatAsError = false;
            }

            if ($method['docblock'] !== null) {
                continue;
            }

            if (true === $treatAsError) {
                $this->fileStatus->add(new MethodError($file->getFileName(), $name, $method['line'], $name));
            } else {
                $this->fileStatus->add(new MethodInfo($file->getFileName(), $name, $method['line'], $name));
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return !$this->config->isSkipMethods();
    }
}
