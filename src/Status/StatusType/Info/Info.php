<?php

namespace PhpDocBlockChecker\Status\StatusType\Info;

use PhpDocBlockChecker\Status\StatusType\StatusType;

abstract class Info extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return '<fg=blue>INFO   </> ' . $this->file . ':' . $this->line . ' - ';
    }
}
