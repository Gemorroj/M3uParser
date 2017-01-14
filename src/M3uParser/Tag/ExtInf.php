<?php

namespace M3uParser\Tag;


class ExtInf
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var int
     */
    private $duration;

    /**
     * @param string $lineStr
     */
    public function __construct($lineStr)
    {
        $this->makeData($lineStr);
    }

    /**
     * @param string $lineStr
     * @see http://l189-238-14.cn.ru/api-doc/m3u-extending.html
     */
    protected function makeData($lineStr)
    {
        /*
EXTINF format:
#EXTINF:<duration> [<attributes-list>], <title>
example:
#EXTINF:-1 tvg-name=Первый_HD tvg-logo="Первый канал" deinterlace=4 group-title="Эфирные каналы",Первый канал HD
         */
        $tmp = substr($lineStr, 8);

        $split = explode(',', $tmp, 2);
        $this->setTitle(trim($split[1]));
        $this->setDuration((int)$split[0]);
    }

    /**
     * @param string $title
     * @return ExtInf
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
     * @return ExtInf
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
}
