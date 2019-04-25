<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Error;

/**
 * Class ClassError
 * @package PhpDocBlockChecker\Status\StatusType\Error
 */
class ClassError extends Error
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'class';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return parent::getDecoratedMessage() . 'Class <info>' . $this->class . '</info> is missing a docblock.';
    }
}
