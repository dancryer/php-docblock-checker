<?php declare(strict_types=1);

namespace PhpDocBlockChecker;

use JsonSerializable;

/**
 * Class FileInfo
 * @package PhpDocBlockChecker
 */
class FileInfo implements JsonSerializable
{
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var mixed[][]
     */
    private $classes;
    /**
     * @var mixed[][]
     */
    private $methods;
    /**
     * @var int
     */
    private $mtime;

    /**
     * FileInfo constructor.
     * @param string $fileName
     * @param mixed[][] $classes
     * @param mixed[][] $methods
     * @param int $mtime
     */
    public function __construct(string $fileName, array $classes, array $methods, int $mtime)
    {
        $this->fileName = $fileName;
        $this->classes = $classes;
        $this->methods = $methods;
        $this->mtime = $mtime;
    }

    /**
     * @param mixed[] $data
     * @return FileInfo
     */
    public static function fromArray(array $data): FileInfo
    {
        return new self($data['fileName'], $data['classes'], $data['methods'], $data['mtime']);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return mixed[][]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return mixed[][]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return int
     */
    public function getMtime(): int
    {
        return $this->mtime;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'fileName' => $this->fileName,
            'mtime' => $this->mtime,
            'classes' => $this->classes,
            'methods' => $this->methods,
        ];
    }
}
