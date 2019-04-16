<?php

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
    public function __construct($file, $class, $line)
    {
        $this->file = $file;
        $this->class = $class;
        $this->line = $line;
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getDecoratedMessage();

    /**
     * @return string
     */
    public function getMessage()
    {
        return strip_tags($this->getDecoratedMessage());
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }
}
