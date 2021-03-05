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

            if ($method['return'] === 'array' && substr($method['docblock']['return'], -2) === '[]') {
                // Do nothing because this is fine.
                continue;
            }

            if ($method['return'] !== $method['docblock']['return']) {
                $this->fileStatus->add(
                    new ReturnMismatchWarning(
                        $file->getFileName(),
                        $name,
                        $method['line'],
                        $name,
                        is_array($method['return']) ? implode('|', $method['return']) : $method['return'],
                        $method['docblock']['return']
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
