<?php

namespace PhpDocBlockChecker\Status\StatusType\Warning;

class ReturnMissingWarning extends Warning
{
    /**
     * @var string
     */
    private $method;

    /**
     * ReturnMissingWarning constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     */
    public function __construct($file, $class, $line, $method)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'return-missing';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method . '</info> - @return missing.';
    }
}
