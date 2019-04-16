<?php

namespace PhpDocBlockChecker\Status\StatusType\Warning;

use PhpDocBlockChecker\Status\StatusType\StatusType;

abstract class Warning extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return '<fg=yellow>WARNING </> ';
    }
}
