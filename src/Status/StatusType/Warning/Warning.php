<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Warning;

use PhpDocBlockChecker\Status\StatusType\StatusType;

/**
 * Class Warning
 * @package PhpDocBlockChecker\Status\StatusType\Warning
 */
abstract class Warning extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return '<fg=yellow>WARNING </> ';
    }
}
