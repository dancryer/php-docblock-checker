<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * @author Neil Brayfield <neil@d3r.com>
 */
interface DocBlockInterface
{
    /**
     * @return bool
     */
    public function isInherited(): bool;

    /**
     * @param bool $inherited
     * @return self
     */
    public function setInherited(bool $inherited): self;
}
