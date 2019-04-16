<?php

namespace PhpDocBlockChecker\DocblockParser;

class ReturnTag extends Tag
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $desc;

    /**
     * ReturnTag constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name, $value);

        $parts = preg_split('/\s+/', $value, 2);

        $this->type = isset($parts[0]) ? $parts[0] : '';
        $this->desc = isset($parts[1]) ? $parts[1] : '';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }
}
