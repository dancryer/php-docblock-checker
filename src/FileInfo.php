<?php

namespace PhpDocBlockChecker;

class FileInfo implements \JsonSerializable
{
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var array
     */
    private $classes;
    /**
     * @var array
     */
    private $methods;
    /**
     * @var int
     */
    private $mtime;

    public function __construct($fileName, $classes, $methods, $mtime)
    {
        $this->fileName = $fileName;
        $this->classes = $classes;
        $this->methods = $methods;
        $this->mtime = $mtime;
    }

    /**
     * @param array $data
     * @return FileInfo
     */
    public static function fromArray(array $data)
    {
        return new self($data['fileName'], $data['classes'], $data['methods'], $data['mtime']);
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return int
     */
    public function getMtime()
    {
        return $this->mtime;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'fileName' => $this->fileName,
            'mtime' => $this->mtime,
            'classes' => $this->classes,
            'methods' => $this->methods,
        ];
    }
}
