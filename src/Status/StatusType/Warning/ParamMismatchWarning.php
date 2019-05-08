<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Warning;

/**
 * Class ParamMismatchWarning
 * @package PhpDocBlockChecker\Status\StatusType\Warning
 */
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
    public function __construct(
        string $file,
        string $class,
        int $line,
        string $method,
        string $param,
        string $paramType,
        string $docType
    )
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
    public function getType(): string
    {
        return 'param-mismatch';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * @return string
     */
    public function getParamType(): string
    {
        return $this->paramType;
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return $this->docType;
    }

    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method . '</info> - @param <fg=blue>' .
            $this->param . '</> (' . $this->docType .
            ')  does not match method signature (' . $this->paramType . ').';
    }
}
