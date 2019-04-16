<?php

namespace PhpDocBlockChecker;

/**
 * Parse the docblock of a function or method
 * @author Paul Scott <paul@duedil.com>
 * {@link http://www.github.com/icio/PHP-DocBlock-Parser}
 */
class DocblockParser
{
    /**
     * Tags in the docblock that have a whitepace-delimited number of parameters
     * (such as `@param type var desc` and `@return type desc`) and the names of
     * those parameters.
     *
     * @type array
     */
    public static $vectors = [
        'param' => ['type', 'var', 'desc'],
        'return' => ['type', 'desc'],
    ];

    /**
     * The description of the symbol
     * @type string
     */
    public $desc;

    /**
     * The tags defined in the docblock.
     *
     * The array has keys which are the tag names (excluding the @) and values
     * that are arrays, each of which is an entry for the tag.
     *
     * In the case where the tag name is defined in {@see DocBlock::$vectors} the
     * value within the tag-value array is an array in itself with keys as
     * described by {@see DocBlock::$vectors}.
     *
     * @type array
     */
    public $tags;

    /**
     * The entire DocBlock comment that was parsed.
     * @type String
     */
    public $comment;

    /**
     * CONSTRUCTOR.
     * @param string $comment The text of the docblock
     */
    public function __construct($comment = null)
    {
        if ($comment) {
            $this->setComment($comment);
        }
    }

    /**
     * Check whether or not this docblock is simply an `inheritdoc` comment.
     * @return bool
     */
    public function isInheritDocComment()
    {
        return $this->hasTag('inheritdoc');
    }

    /**
     * Set and parse the docblock comment.
     * @param string $comment The docblock
     */
    public function setComment($comment)
    {
        $this->desc = '';
        $this->tags = array();
        $this->comment = $comment;

        $this->parseComment($comment);
    }

    /**
     * Parse the comment into the component parts and set the state of the object.
     * @param string $comment The docblock
     */
    protected function parseComment($comment)
    {
        // Strip the opening and closing tags of the docblock
        $comment = substr($comment, 3, -2);

        // Split into arrays of lines
        $commentLines = preg_split('/\r?\n\r?/', $comment);
        if ($commentLines === false) {
            return;
        }

        // Trim asterisks and whitespace from the beginning and whitespace from the end of lines
        $commentLines = array_map(function ($line) {
            return ltrim(rtrim($line), "* \t\n\r\0\x0B");
        }, $commentLines);

        // Group the lines together by @tags
        $blocks = array();
        $b = -1;
        foreach ($commentLines as $line) {
            if (self::isTagged($line)) {
                $b++;
                $blocks[] = array();
            } elseif ($b === -1) {
                $b = 0;
                $blocks[] = array();
            }
            $blocks[$b][] = $line;
        }

        // Parse the blocks
        foreach ($blocks as $block => $body) {
            $body = trim(implode("\n", $body));

            if ($block === 0 && !self::isTagged($body)) {
                // This is the description block
                $this->desc = $body;
                continue;
            }

            $tagstr = self::strTag($body);
            if ($tagstr === null) {
                continue;
            }

            // This block is tagged
            $tag = substr($tagstr, 1);
            $body = ltrim(substr($body, strlen($tag) + 2));

            if (isset(self::$vectors[$tag])) {
                // The tagged block is a vector
                $count = count(self::$vectors[$tag]);
                $parts = $body ? preg_split('/\s+/', $body, $count) : [];
                if (!is_array($parts)) {
                    continue;
                }

                // Default the trailing values
                $parts = array_pad($parts, $count, null);
                // Store as a mapped array
                $this->tags[$tag][] = array_combine(
                    self::$vectors[$tag],
                    $parts
                );
            } else {
                // The tagged block is only text
                $this->tags[$tag][] = $body;
            }
        }
    }

    /**
     * Whether or not a docblock contains a given @tag.
     * @param string $tag The name of the @tag to check for
     * @return bool
     */
    public function hasTag($tag)
    {
        return is_array($this->tags) && array_key_exists($tag, $this->tags);
    }

    /**
     * The value of a tag
     * @param String $tag
     * @return array
     */
    public function tag($tag)
    {
        return $this->hasTag($tag) ? $this->tags[$tag] : null;
    }

    /**
     * The value of a tag (concatenated for multiple values)
     * @param string $tag
     * @param string $sep The seperator for concatenating
     * @return string|null
     */
    public function tagImplode($tag, $sep = ' ')
    {
        return $this->hasTag($tag) ? implode($sep, $this->tags[$tag]) : null;
    }

    /**
     * The value of a tag (merged recursively)
     * @param string $tag
     * @return array|null
     */
    public function tagMerge($tag)
    {
        return $this->hasTag($tag) ? array_merge_recursive($this->tags[$tag]) : null;
    }

    /*
     * ==================================
     */

    /**
     * Whether or not a string begins with a @tag
     * @param string $str
     * @return bool
     */
    public static function isTagged($str)
    {
        return isset($str[1]) && $str[0] == '@' && ctype_alpha($str[1]);
    }

    /**
     * The tag at the beginning of a string
     * @param string $str
     * @return string|null
     */
    public static function strTag($str)
    {
        if (preg_match('/^@[a-z0-9_]+/', $str, $matches)) {
            return $matches[0];
        }
        return null;
    }
}
