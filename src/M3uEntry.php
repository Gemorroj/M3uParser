<?php

namespace M3uParser;

use M3uParser\Tag\ExtTagInterface;

class M3uEntry
{
    /**
     * @var string
     */
    protected $lineDelimiter = "\n";
    /**
     * @var ExtTagInterface[]
     */
    private $extTags = [];
    /**
     * @var string|null
     */
    private $path;

    /**
     * @return ExtTagInterface[]
     */
    public function getExtTags()
    {
        return $this->extTags;
    }

    /**
     * @param ExtTagInterface $extTag
     * @return $this
     */
    public function addExtTag(ExtTagInterface $extTag)
    {
        $this->extTags[] = $extTag;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $out = '';
        foreach ($this->getExtTags() as $extTag) {
            $out .= (string)$extTag . $this->lineDelimiter;
        }

        $out .= (string)$this->getPath();

        return \rtrim($out);
    }
}
