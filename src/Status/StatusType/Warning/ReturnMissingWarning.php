<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Warning;

/**
 * Class ReturnMissingWarning
 * @package PhpDocBlockChecker\Status\StatusType\Warning
 */
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
    public function __construct(string $file, string $class, int $line, string $method)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'return-missing';
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
    public function getDecoratedMessage(): string
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method . '</info> - @return missing.';
    }
}
