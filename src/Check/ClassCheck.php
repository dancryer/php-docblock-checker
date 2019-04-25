<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Error\ClassError;

/**
 * Class ClassCheck
 * @package PhpDocBlockChecker\Check
 */
class ClassCheck extends Check
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file): void
    {
        foreach ($file->getClasses() as $name => $class) {
            if ($class['docblock'] !== null) {
                continue;
            }

            $this->fileStatus->add(new ClassError($file->getFileName(), $name, $class['line']));
        }
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return !$this->config->isSkipClasses();
    }
}
