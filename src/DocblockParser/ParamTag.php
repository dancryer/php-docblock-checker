<?php declare(strict_types=1);

namespace PhpDocBlockChecker\DocblockParser;

/**
 * Class ParamTag
 * @package PhpDocBlockChecker\DocblockParser
 */
class ParamTag extends Tag
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $var;
    /**
     * @var string
     */
    private $desc;

    /**
     * ParamTag constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        parent::__construct($name, $value);

        $parts = preg_split('/\s+/', $value, 3);

        if ($parts === false) {
            return;
        }

        $this->type = $parts[0] ?? '';
        $this->var = $parts[1] ?? '';
        $this->desc = $parts[2] ?? '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVar(): string
    {
        return $this->var;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }
}
