<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMismatchWarning;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMissingWarning;

class ReturnCheck extends Check
{

    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getMethods() as $name => $method) {
            $docblock = $method->getDocblock();
            if ($method->getReturnType() === null) {
                // Nothing to check.
                continue;
            }

            if (empty($docblock['return'])) {
                $this->fileStatus->add(
                    new ReturnMissingWarning(
                        $file->getFileName(),
                        $name,
                        $method->getLine(),
                        $name
                    )
                );
                continue;
            }

            $returnTypes = $docblock['return'];
            $methodTypes = $method->getReturnType();

            if ($method->getReturnType() === 'array'
                && !is_array($returnTypes)
                && substr($returnTypes, -2) === '[]'
            ) {
                // Do nothing because this is fine.
                continue;
            }

            if ($methodTypes !== $returnTypes) {
                $this->fileStatus->add(
                    new ReturnMismatchWarning(
                        $file->getFileName(),
                        $name,
                        $method->getLine(),
                        $name,
                        $methodTypes->toString(),
                        is_array($returnTypes) ? implode('|', $returnTypes) : $returnTypes
                    )
                );
                continue;
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipSignatures();
    }
}
