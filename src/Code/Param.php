<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * A type with a name
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class Param extends AbstractType
{
    /** @var string */
    protected $name;

    /** @var bool */
    protected $variadic = false;

    /**
     * @param string $name
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setName(string $name): self
    {
        if (strpos($name, '$') !== 0) {
            $name = '$' . $name;
        }

        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param bool $bool
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setVariadic(bool $bool): self
    {
        $this->variadic = $bool;

        return $this;
    }

    /**
     * Is this a variadic param
     *
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function isVariadic(): bool
    {
        return $this->variadic;
    }
}
