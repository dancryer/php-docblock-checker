<?php

namespace PhpDocBlockChecker\Status\StatusType\Info;

class MethodInfo extends Info
{
    /**
     * @var string
     */
    private $method;

    public function __construct($file, $class, $line, $method)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function getType()
    {
        return 'method';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . 'Method <info>' . $this->method . '</info> is missing a docblock.';
    }
}
