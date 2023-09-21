<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Represents a type and contains comparison functions for dealing with
 * composite types
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
abstract class AbstractType extends AbstractClassCode
{
    /** @var array */
    protected $types = [];

    /** @var bool */
    protected $nullable = false;

    /**
     * @param bool $bool
     * @return self
     */
    public function setNullable(bool $bool): self
    {
        $this->nullable = $bool;

        return $this;
    }


    /**
     * @param array $types
     * @return self
     */
    public function addTypes(array $types): self
    {
        foreach ($types as $type) {
            $this->addType($type);
        }

        return $this;
    }

    /**
     * @param string $type
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addType(string $type): self
    {
        $this->types[] = new SubType($type, $this);
        return $this;
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __toString()
    {
        return implode('|', $this->types);
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function toString(): string
    {
        return $this->__toString();
    }
}
