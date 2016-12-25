<?php
/**
 *
 * This software is distributed under the GNU GPL v3.0 license.
 *
 * @author    Gemorroj
 * @copyright 2015 http://wapinet.ru
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/Gemorroj/M3uParser
 *
 */

namespace M3uParser\Tag;


class ExtTv
{
    /**
     * @var string[]
     */
    private $tags;
    /**
     * @var string|null
     */
    private $language;
    /**
     * @var string|null
     */
    private $xmlTvId;
    /**
     * @var string|null
     */
    private $iconUrl;

    /**
     * @param string $lineStr
     */
    public function __construct($lineStr)
    {
        $this->makeData($lineStr);
    }

    /**
     * @param string $lineStr
     */
    protected function makeData($lineStr)
    {
        //todo
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
     * @return ExtTv
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return ExtTv
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getXmlTvId()
    {
        return $this->xmlTvId;
    }

    /**
     * @param string $xmlTvId
     * @return ExtTv
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
     * @return ExtTv
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }
}
