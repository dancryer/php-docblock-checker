<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Is similar to a method but represents a docblock
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class ClassDocBlock extends AbstractCode implements DocBlockInterface
{
    use DocBlockTrait;
}
