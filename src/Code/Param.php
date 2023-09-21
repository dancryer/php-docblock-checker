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
        $this->name = $name;

        return $this;
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
}
