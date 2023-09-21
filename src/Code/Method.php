<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Undocumented model
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class Method extends AbstractClassCode
{
    /**
     * @var bool
     */
    protected $hasReturn = false;


    /**
     * @var \PhpDocBlockChecker\Code\ReturnType
     * @author Neil Brayfield <neil@d3r.com>
     */
    protected $returnType;

    /**     *
     * @param bool $bool
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setHasReturn(bool $bool): self
    {
        $this->hasReturn = $bool;

        return $this;
    }

    /**
     * @param ReturnType $returnType
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setReturnType(ReturnType $returnType): self
    {
        $this->returnType = $returnType;

        return $this;
    }
}
