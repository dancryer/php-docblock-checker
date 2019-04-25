<?php declare(strict_types=1);

namespace PhpDocBlockChecker\DocblockParser;

/**
 * Class TagCollection
 * @package PhpDocBlockChecker\DocblockParser
 */
class TagCollection
{
    /**
     * @var string[]
     */
    private $tagNames = [];

    /**
     * @var Tag[]
     */
    private $tags = [];

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag): void
    {
        if (!isset($this->tagNames[$tag->getName()])) {
            $this->tagNames[$tag->getName()] = $tag->getName();
        }

        $this->tags[] = $tag;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasTag(string $name): bool
    {
        return isset($this->tagNames[$name]);
    }

    /**
     * @return ParamTag[]
     */
    public function getParamTags(): array
    {
        return array_values(
            array_filter($this->tags, static function (Tag $tag) {
                return $tag instanceof ParamTag;
            })
        );
    }

    /**
     * @return ReturnTag[]
     */
    public function getReturnTags(): array
    {
        return array_values(
            array_filter($this->tags, static function (Tag $tag) {
                return $tag instanceof ReturnTag;
            })
        );
    }
}
