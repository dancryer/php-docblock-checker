<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMismatchWarning;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMissingWarning;

/**
 * Class ReturnCheck
 * @package PhpDocBlockChecker\Check
 */
class ReturnCheck extends Check
{

    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file): void
    {
        foreach ($file->getMethods() as $name => $method) {
            if ($method['return'] === null) {
                continue;
            }

            if ($method['docblock']['return'] === null) {
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

            if ($method['docblock']['return'] === $method['return']) {
                continue;
            }

            if ($method['return'] === 'array' && substr($method['docblock']['return'], -2) === '[]') {
                // Do nothing because this is fine.
            } else {
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

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return !$this->config->isSkipSignatures();
    }
}
