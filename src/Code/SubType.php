<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Represents a single type not a composite type
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class SubType extends AbstractClassCode
{
    /**
     * @var \PhpDocBlockChecker\Code\AbstractClassCode
     * @author Neil Brayfield <neil@d3r.com>
     */
    protected $parent;

    /**
     * @var string
     * @author Neil Brayfield <neil@d3r.com>
     */
    protected $type;

    /**
     * @param string $type
     * @param \PhpDocBlockChecker\Code\AbstractClassCode $parent
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __construct(string $type, AbstractClassCode $parent)
    {
        $this->parent = $parent;
        $this->type = $type;
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __toString()
    {
        return $this->type;
    }
}
