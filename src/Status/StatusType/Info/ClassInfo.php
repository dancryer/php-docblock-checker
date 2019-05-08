<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Info;

/**
 * Class ClassInfo
 * @package PhpDocBlockChecker\Status\StatusType\Info
 */
class ClassInfo extends Info
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
