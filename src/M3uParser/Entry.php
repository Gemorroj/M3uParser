<?php

namespace M3uParser;

use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class Entry
{
    /**
     * @var ExtInf|null
     */
    private $extInf;
    /**
     * @var ExtTv|null
     */
    private $extTv;
    /**
     * @var string
     */
    private $path;

    /**
     * @return ExtInf|null
     */
    public function getExtInf()
    {
        return $this->extInf;
    }

    /**
     * @param ExtInf $extInf
     * @return Entry
     */
    public function setExtInf(ExtInf $extInf)
    {
        $this->extInf = $extInf;
        return $this;
    }

    /**
     * @return ExtTv|null
     */
    public function getExtTv()
    {
        return $this->extTv;
    }

    /**
     * @param ExtTv $extTv
     * @return Entry
     */
    public function setExtTv(ExtTv $extTv)
    {
        $this->extTv = $extTv;
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
     * @return Entry
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}
