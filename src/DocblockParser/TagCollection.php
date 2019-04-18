<?php

namespace PhpDocBlockChecker\DocblockParser;

class TagCollection
{
    /**
     * @var array
     */
    private $tagNames = [];

    /**
     * @var Tag[]
     */
    private $tags = [];

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        if (!isset($this->tagNames[$tag->getName()])) {
            $this->tagNames[$tag->getName()] = $tag->getName();
        }

        $this->tags[] = $tag;
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasTag($name)
    {
        return isset($this->tagNames[$name]);
    }

    /**
     * @return ParamTag[]
     */
    public function getParamTags()
    {
        return array_filter($this->tags, static function (Tag $tag) {
            return $tag instanceof ParamTag;
        });
    }

    /**
     * @return ReturnTag[]
     */
    public function getReturnTags()
    {
        return array_filter($this->tags, static function (Tag $tag) {
            return $tag instanceof ReturnTag;
        });
    }
}
