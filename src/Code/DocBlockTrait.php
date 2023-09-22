<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Trait for common docblock code
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
trait DocBlockTrait
{
    /** @var bool */
    protected $inherited = false;

    /**
     * @param bool $inherited
     * @return self
     */
    public function setInherited(bool $inherited): self
    {
        $this->inherited = $inherited;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInherited(): bool
    {
        return $this->inherited;
    }
}
