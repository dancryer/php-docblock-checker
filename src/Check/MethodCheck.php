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
            $params = $method->getParams();
            if (false === $method->hasReturn() &&
                $this->config->isOnlySignatures() &&
                (empty($params) || 0 === count($params))) {
                $treatAsError = false;
            }

            if ($method->getDocblock() === null) {
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
