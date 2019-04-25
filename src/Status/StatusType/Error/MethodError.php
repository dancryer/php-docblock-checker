<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Error;

/**
 * Class MethodError
 * @package PhpDocBlockChecker\Status\StatusType\Error
 */
class MethodError extends Error
{
    /**
     * @var string
     */
    private $method;

    /**
     * MethodError constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     */
    public function __construct(string $file, string $class, int $line, string $method)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
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
    public function getType(): string
    {
        return 'method';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return parent::getDecoratedMessage() . 'Method <info>' . $this->method . '</info> is missing a docblock.';
    }
}
