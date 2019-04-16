<?php

namespace PhpDocBlockChecker\Status\StatusType\Error;

use PhpDocBlockChecker\Status\StatusType\StatusType;

abstract class Error extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return '<fg=red>ERROR   </> ' . $this->file . ':' . $this->line . ' - ';
    }
}
