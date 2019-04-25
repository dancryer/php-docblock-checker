<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Info;

use PhpDocBlockChecker\Status\StatusType\StatusType;

/**
 * Class Info
 * @package PhpDocBlockChecker\Status\StatusType\Info
 */
abstract class Info extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return '<fg=blue>INFO   </> ' . $this->file . ':' . $this->line . ' - ';
    }
}
