<?php

namespace M3uParser\Tag;

use M3uParser\TagAttributesTrait;

class ExtInf implements ExtTagInterface
{
    use TagAttributesTrait;

    /**
     * @var string
     */
    private $title;
    /**
     * @var int
     */
    private $duration;

    /**
     * #EXTINF:-1 tvg-name=Первый_HD tvg-logo="Первый канал" deinterlace=4 group-title="Эфирные каналы",Первый канал HD
     *
     * @param string $lineStr
     */
    public function __construct($lineStr = null)
    {
        if (null !== $lineStr) {
            $this->make($lineStr);
        }
    }

    /**
     * @param string $lineStr
     * @see http://l189-238-14.cn.ru/api-doc/m3u-extending.html
     */
    protected function make($lineStr)
    {
        /*
EXTINF format:
#EXTINF:<duration> [<attributes-list>], <title>
example:
#EXTINF:-1 tvg-name=Первый_HD tvg-logo="Первый канал" deinterlace=4 group-title="Эфирные каналы",Первый канал HD
         */
        $tmp = \substr($lineStr, 8);

        // Parse duration and title with regex
        preg_match('/^(-?\d+)\s*(?:(?:[^=]+=["\'][^"\']*["\'])|(?:[^=]+=[^ ]*))*,(.*)$/', $tmp, $matches);

        $duration = (int)$matches[1];
        $title = \trim($matches[2]);

        $this->setTitle($title);
        $this->setDuration($duration);

        // Attributes are remaining string after remove duration and title
        $attributes = preg_replace('#^'.preg_quote($matches[1]).'(.*)'.preg_quote($matches[2]).'$#', '$1', $tmp);

        $splitAttributes = \explode(' ', $attributes, 2);

        if (isset($splitAttributes[1]) && \trim($splitAttributes[1])) {
            $this->initAttributes(\trim($splitAttributes[1]));
        }
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '#EXTINF: ' . (int)$this->getDuration() . ' ' . $this->getAttributesString() . ', ' . $this->getTitle();
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    public static function isMatch($lineStr)
    {
        return '#EXTINF:' === \strtoupper(\substr($lineStr, 0, 8));
    }
}
