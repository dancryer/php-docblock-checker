<?php

namespace PhpDocBlockChecker\DocblockParser;

/**
 * Class DocblockParser
 * @package PhpDocBlockChecker\DocblockParser
 */
class DocblockParser
{
    /**
     * Parse the comment into the component parts and set the state of the object.
     * @param string $comment The docblock
     * @return TagCollection
     */
    public function parseComment($comment)
    {
        preg_match_all('/@([a-zA-Z]+) *(.*)\n/', $comment, $matches, PREG_SET_ORDER);
        $tags = new TagCollection();

        foreach ($matches as $match) {
            array_shift($match);
            list($tag, $body) = $match;

            $tags->addTag($this->getTagEntity($tag, $body));
        }

        return $tags;
    }

    /**
     * @param string $tag
     * @param string $body
     * @return ParamTag|ReturnTag|Tag
     */
    private function getTagEntity($tag, $body)
    {
        if ($tag === 'param') {
            return new ParamTag($tag, $body);
        }

        if ($tag === 'return') {
            return new ReturnTag($tag, $body);
        }

        return new Tag($tag, $body);
    }
}
