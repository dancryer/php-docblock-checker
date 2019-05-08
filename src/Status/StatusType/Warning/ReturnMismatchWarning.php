<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Status\StatusType\Warning;

/**
 * Class ReturnMismatchWarning
 * @package PhpDocBlockChecker\Status\StatusType\Warning
 */
class ReturnMismatchWarning extends Warning
{
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $returnType;
    /**
     * @var string
     */
    private $docType;

    /**
     * ReturnMismatchWarning constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     * @param string $returnType
     * @param string $docType
     */
    public function __construct(
        string $file,
        string $class,
        int $line,
        string $method,
        string $returnType,
        string $docType
    )
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
        $this->returnType = $returnType;
        $this->docType = $docType;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'return-mismatch';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getReturnType(): string
    {
        return $this->returnType;
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return $this->docType;
    }

    /**
     * @return string
     */
    public function getDecoratedMessage(): string
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method .
            '</info> - @return <fg=blue>' . $this->docType .
            '</>  does not match method signature (' . $this->returnType . ').';
    }
}
