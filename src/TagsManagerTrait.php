<?php

namespace M3uParser;

use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtLogo;
use M3uParser\Tag\ExtTagInterface;
use M3uParser\Tag\ExtTv;

trait TagsManagerTrait
{
    /**
     * @var class-string<ExtTagInterface>[]
     */
    private $tags = [];

    /**
     * Add tag.
     *
     * @param class-string<ExtTagInterface> $tag class name. Must implements the ExtTagInterface interface
     *
     * @return $this
     */
    public function addTag(string $tag): self
    {
        $implements = @\class_implements($tag);
        if (false === $implements) {
            throw new Exception(\sprintf('Unknown class %s', $tag));
        }
        if (!\in_array(ExtTagInterface::class, $implements, true)) {
            throw new Exception(\sprintf('The class %s must implements the %s interface', $tag, ExtTagInterface::class));
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
     * @return class-string<ExtTagInterface>[]
     */
    protected function getTags(): array
    {
        return $this->tags;
    }
}
