<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType;

/**
 * Class StatusType
 * @package PhpDocBlockChecker\Status\StatusType
 */
abstract class StatusType
{
    /**
     * @var string
     */
    protected $file;
    /**
     * @var string
     */
    protected $class;
    /**
     * @var int
     */
    protected $line;

    /**
     * StatusType constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     */
    public function __construct(string $file, string $class, int $line)
    {
        $this->file = $file;
        $this->class = $class;
        $this->line = $line;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return string
     */
    abstract public function getDecoratedMessage(): string;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return strip_tags($this->getDecoratedMessage());
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }
}
