<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Error\ClassError;

class ClassCheck extends Check
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getClasses() as $name => $class) {
            if ($class['docblock'] === null) {
                $this->fileStatus->add(new ClassError($file->getFileName(), $name, $class['line']));
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipClasses();
    }
}
