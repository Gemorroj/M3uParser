<?php

namespace M3uParser;

use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTagInterface;
use M3uParser\Tag\ExtTv;

trait TagsManagerTrait
{
    private $tags = [];

    /**
     * Add tag
     *
     * @param string $tag class name must be implement ExtTagInterface interface
     * @throws Exception
     * @return $this
     */
    public function addTag($tag)
    {
        if (!\in_array(ExtTagInterface::class, \class_implements($tag), true)) {
            throw new Exception(\sprintf('The class %s must be implement interface %s', $tag, ExtTagInterface::class));
        }

        $this->tags[] = $tag;
        return $this;
    }

    /**
     * Add default tags (EXTINF and EXTTV)
     *
     * @throws Exception
     * @return $this
     */
    public function addDefaultTags()
    {
        $this->addTag(ExtInf::class);
        $this->addTag(ExtTv::class);
        return $this;
    }

    /**
     * Remove all previously defined tags
     *
     * @return $this
     */
    public function clearTags()
    {
        $this->tags = [];
        return $this;
    }

    /**
     * Get all active tags
     *
     * @return string[]
     */
    protected function getTags()
    {
        return $this->tags;
    }
}
