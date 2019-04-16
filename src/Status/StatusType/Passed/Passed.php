<?php

namespace PhpDocBlockChecker\Status\StatusType\Passed;

use PhpDocBlockChecker\Status\StatusType\StatusType;

class Passed extends StatusType
{

    /**
     * Passed constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file, '', 0);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'passed';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        // TODO: Implement getDecoratedMessage() method.
    }
}
