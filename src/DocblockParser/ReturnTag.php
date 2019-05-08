<?php declare(strict_types=1);

namespace PhpDocBlockChecker\DocblockParser;

/**
 * Class ReturnTag
 * @package PhpDocBlockChecker\DocblockParser
 */
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
    public function __construct(string $name, string $value)
    {
        parent::__construct($name, $value);

        $parts = preg_split('/\s+/', $value, 2);

        if ($parts === false) {
            return;
        }

        $this->type = $parts[0] ?? '';
        $this->desc = $parts[1] ?? '';
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
    public function getDesc(): string
    {
        return $this->desc;
    }
}
