<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

use PhpDocBlockChecker\Code\SubType;

/**
 * Is similar to a method but represents a docblock
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class MethodDocBlock extends Method implements DocBlockInterface
{
    use DocBlockTrait;
}
