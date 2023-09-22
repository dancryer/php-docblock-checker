<?php

namespace PhpDocBlockChecker\Status\StatusType\Warning;

class ParamMismatchWarning extends Warning
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
     * @var string
     */
    private $paramType;
    /**
     * @var string
     */
    private $docType;

    /**
     * ParamMismatchWarning constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     * @param string $param
     * @param string $paramType
     * @param string $docType
     */
    public function __construct($file, $class, $line, $method, $param, $paramType, $docType)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
        $this->param = $param;
        $this->paramType = $paramType;
        $this->docType = $docType;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'param-mismatch';
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
    public function getParamType()
    {
        return $this->paramType;
    }

    /**
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method . '</info> - @param <fg=blue>' .
            $this->param . '</> (' . $this->docType .
            ') does not match method signature <fg=blue>' . $this->paramType . '</>.';
    }
}
