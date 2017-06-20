<?php

namespace M3uParser\Tag;


class ExtTv implements ExtTagInterface
{
    /**
     * @var string[]
     */
    private $tags;
    /**
     * @var string
     */
    private $language;
    /**
     * @var string
     */
    private $xmlTvId;
    /**
     * @var string|null
     */
    private $iconUrl;

    /**
     * #EXTTV:nacionalni,hd;slovenski;SLO1;http://cdn1.siol.tv/logo/93x78/slo2.png
     * @param string $lineStr
     */
    public function __construct($lineStr = null)
    {
        if (null !== $lineStr) {
            $this->makeData($lineStr);
        }
    }

    /**
     * @param string $lineStr
     * @see https://github.com/Gemorroj/M3uParser/issues/5
     */
    protected function makeData($lineStr)
    {
        /*
EXTTV format:
#EXTTV:tag[,tag,tag...];language;XMLTV id[;icon URL]
example:
#EXTTV:nacionalni,hd;slovenski;SLO1;http://cdn1.siol.tv/logo/93x78/slo2.png
         */

        $tmp = \substr($lineStr, 7);
        $split = \explode(';', $tmp, 4);

        $this->setTags(\array_map('trim', \explode(',', $split[0])));
        $this->setLanguage(\trim($split[1]));
        $this->setXmlTvId(\trim($split[2]));
        if (isset($split[3])) {
            $this->setIconUrl(\trim($split[3]));
        }
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getXmlTvId()
    {
        return $this->xmlTvId;
    }

    /**
     * @param string $xmlTvId
     * @return $this
     */
    public function setXmlTvId($xmlTvId)
    {
        $this->xmlTvId = $xmlTvId;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * @param string $iconUrl
     * @return $this
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '#EXTTV: ' . \implode(',', $this->getTags()) . ';' . $this->getLanguage() . ';' . $this->getXmlTvId() . ($this->getIconUrl() ? ';' . $this->getIconUrl() : '');
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    public static function isMatch($lineStr)
    {
        return '#EXTTV:' === \strtoupper(\substr($lineStr, 0, 7));
    }
}
