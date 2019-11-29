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
            if (!empty($method['return'])) {
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

                if (is_array($method['return'])) {
                    $docblockTypes = explode('|', $method['docblock']['return']);
                    sort($docblockTypes);
                    if ($method['return'] !== $docblockTypes) {
                        $this->fileStatus->add(
                            new ReturnMismatchWarning(
                                $file->getFileName(),
                                $name,
                                $method['line'],
                                $name,
                                implode('|', $method['return']),
                                $method['docblock']['return']
                            )
                        );
                        continue;
                    }
                }

                if ($method['docblock']['return'] !== $method['return']) {
                    if ($method['return'] === 'array' && substr($method['docblock']['return'], -2) === '[]') {
                        // Do nothing because this is fine.
                    } else {
                        if (!is_array($method['return']) || !$this->checkMultipleReturnStatements($method)) {
                            $this->fileStatus->add(
                                new ReturnMismatchWarning(
                                    $file->getFileName(),
                                    $name,
                                    $method['line'],
                                    $name,
                                    $method['return'],
                                    $method['docblock']['return']
                                )
                            );
                        }
                    }
                }
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

    /**
     * @param array $method
     * @return bool
     */
    private function checkMultipleReturnStatements(array $method): bool
    {
        $dockReturn = explode('|', $method['docblock']['return']);
        $methodReturn = $method['return'];

        return count(array_diff($dockReturn, $methodReturn)) == 0 && count(array_diff($methodReturn, $dockReturn)) == 0;
    }
}
