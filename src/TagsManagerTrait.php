<?php

namespace M3uParser;

use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtLogo;
use M3uParser\Tag\ExtTagInterface;
use M3uParser\Tag\ExtTv;

trait TagsManagerTrait
{
    /**
     * @var array
     */
    private $tags = [];

    /**
     * Add tag.
     *
     * @param string $tag class name must be implement ExtTagInterface interface
     *
     * @return $this
     */
    public function addTag(string $tag): self
    {
        if (!\in_array(ExtTagInterface::class, \class_implements($tag), true)) {
            throw new Exception(\sprintf('The class %s must be implement interface %s', $tag, ExtTagInterface::class));
        }

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Add default tags (EXTINF, EXTTV, EXTLOGO).
     *
     * @return $this
     */
    public function addDefaultTags(): self
    {
        $this->addTag(ExtInf::class);
        $this->addTag(ExtTv::class);
        $this->addTag(ExtLogo::class);

        return $this;
    }

    /**
     * Remove all previously defined tags.
     *
     * @return $this
     */
    public function clearTags(): self
    {
        $this->tags = [];

        return $this;
    }

    /**
     * Get all active tags.
     *
     * @return string[]
     */
    protected function getTags(): array
    {
        return $this->tags;
    }
}
