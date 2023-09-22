<?php

namespace PhpDocBlockChecker\DocblockParser;

class ParamTag extends Tag
{
    /**
     * @var string
     */
    private $type = '';
    /**
     * @var string
     */
    private $var = '';
    /**
     * @var string
     */
    private $desc = '';

    /**
     * @var string
     * @author Neil Brayfield <neil@d3r.com>
     */
    private $variadic = false;

    /**
     * ParamTag constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name, $value);

        $parts = preg_split('/\s+/', $value, 3);

        if ($parts === false) {
            return;
        }

        if (isset($parts[0])) {
            $this->type = $parts[0];
        }
        if (isset($parts[1])) {
            $this->parseName($parts[1]);
        }
        if (isset($parts[2])) {
            $this->desc = $parts[2];
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function isVariadic(): bool
    {
        return $this->variadic;
    }

    /**
     * Parse the name working out if it's variadic or not
     *
     * @param string $name
     * @author Neil Brayfield <neil@d3r.com>
     */
    private function parseName(string $name): void
    {
        $name = trim($name);

        if (preg_match("/,\.\.\.$/", $name)) {
            $this->variadic = true;
            $name = substr($name, 0, -4);
        }

        if (preg_match("/^\.\.\.\$/", $name)) {
            $this->variadic = true;
            $name = substr($name, 3);
        }

        $this->var = $name;
    }
}
