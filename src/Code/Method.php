<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

use PhpDocBlockChecker\Code\Param;

/**
 * Undocumented model
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class Method extends AbstractClassCode
{

    /** @var string */
    protected $name;

    /** @var array */
    protected $docBlock;

    /** @var bool */
    protected $hasReturn = false;

    /** @var \PhpDocBlockChecker\Code\ReturnType */
    protected $returnType;

    /** @var \PhpDocBlockChecker\Code\Param[] */
    protected $params = [];

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
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function hasReturn(): bool
    {
        return $this->hasReturn;
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

    /**
     * Get the return type
     *
     * @return ReturnType|null
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getReturnType(): ?ReturnType
    {
        return $this->returnType;
    }

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
     * Set the docblock
     *
     * @param array|null $docblock
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setDocBlock(array $docblock = null): self
    {
        $this->docBlock = $docblock;

        return $this;
    }

    /**
     * @return array|null
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getDocblock(): ?array
    {
        return $this->docBlock;
    }

    /**
     * @param \PhpDocBlockChecker\Code\Param $param
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addParam(Param $param): self
    {
        $this->params[] = $param;

        return $this;
    }

    /**
     * @return \PhpDocBlockChecker\Code\Param[]
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
