<?php declare(strict_types=1);

namespace PhpDocBlockChecker\DocblockParser;

/**
 * Class Tag
 * @package PhpDocBlockChecker\DocblockParser
 */
class Tag
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

    /**
     * Tag constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
