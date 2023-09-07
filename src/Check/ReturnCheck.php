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
            if (empty($method['return'])) {
                // Nothing to check.
                continue;
            }

            if (empty($method['docblock']['return'])) {
                $this->fileStatus->add(
                    new ReturnMissingWarning(
                        $file->getFileName(),
                        $name,
                        $method['line'],
                        $name
                    )
                );
                continue;
            }

            $returnTypes = $method['docblock']['return'];
            $methodTypes = $method['return'];

            if ($method['return'] === 'array'
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
                        $method['line'],
                        $name,
                        is_array($methodTypes) ? implode('|', $methodTypes) : $methodTypes,
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
