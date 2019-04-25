<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Passed;

use PhpDocBlockChecker\Status\StatusType\StatusType;

/**
 * Class Passed
 * @package PhpDocBlockChecker\Status\StatusType\Passed
 */
class Passed extends StatusType
{

    /**
     * Passed constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct($file, '', 0);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'passed';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return '.';
    }
}
