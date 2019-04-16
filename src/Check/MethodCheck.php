<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Error\MethodError;
use PhpDocBlockChecker\Status\StatusType\Info\MethodInfo;

class MethodCheck extends Check
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getMethods() as $name => $method) {
            $treatAsError = true;
            if (false === $method['has_return'] &&
                $this->config->isOnlySignatures() &&
                (empty($method['params']) || 0 === count($method['params']))) {
                $treatAsError = false;
            }

            if ($method['docblock'] === null) {
                if (true === $treatAsError) {
                    $this->fileStatus->add(new MethodError($file->getFileName(), $name, $method['line'], $name));
                } else {
                    $this->fileStatus->add(new MethodInfo($file->getFileName(), $name, $method['line'], $name));
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipMethods();
    }
}
