<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Error;

use PhpDocBlockChecker\Status\StatusType\StatusType;

/**
 * Class Error
 * @package PhpDocBlockChecker\Status\StatusType\Error
 */
abstract class Error extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return '<fg=red>ERROR   </> ' . $this->file . ':' . $this->line . ' - ';
    }
}
