<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Warning;

/**
 * Class ParamMissingWarning
 * @package PhpDocBlockChecker\Status\StatusType\Warning
 */
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
    public function __construct(string $file, string $class, int $line, string $method, string $param)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'param-missing';
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
    public function getDecoratedMessage(): string
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method .
            '</info> - @param <fg=blue>' . $this->param . '</> missing.';
    }
}
