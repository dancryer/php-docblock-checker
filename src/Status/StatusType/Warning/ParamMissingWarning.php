<?php

namespace PhpDocBlockChecker\Status\StatusType\Warning;

class ParamMissingWarning extends Warning
{
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $param;

    /**
     * ParamMissingWarning constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     * @param string $param
     */
    public function __construct($file, $class, $line, $method, $param)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'param-missing';
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
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method . '</info> - @param <fg=blue>' . $this->param . '</> missing.';
    }
}
