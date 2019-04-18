<?php

namespace PhpDocBlockChecker\FileProvider;

class StdinFileProvider implements FileProviderInterface
{
    use ExcludeFileTrait;

    /**
     * @var resource
     */
    private $handle;

    /**
     * StdinFileProvider constructor.
     * @param resource $handle
     * @param array $excludes
     */
    public function __construct($handle, array $excludes)
    {
        $this->handle = $handle;
        $this->excludes = $excludes;
    }

    public function __destruct()
    {
        // Close the file handle if its still open
        if ($this->fileHandleOpen()) {
            fclose($this->handle);
        }
    }

    private function fileHandleOpen()
    {
        return get_resource_type($this->handle) === 'file';
    }

    /**
     * @return \ArrayIterator
     */
    public function getFileIterator()
    {
        if (!$this->fileHandleOpen()) {
            return new \ArrayIterator();
        }

        $files = [];
        while (($line = fgets($this->handle)) !== false) {
            $line = rtrim($line, "\r\n");
            $file = new \SplFileInfo($line);
            if ($this->isFileExcluded('', $file)) {
                continue;
            }
            $files[] = $file;
        }

        return new \ArrayIterator($files);
    }
}
