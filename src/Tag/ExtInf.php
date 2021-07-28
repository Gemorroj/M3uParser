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
     * @var float
     */
    private $duration;

    /**
     * #EXTINF:-1 tvg-name=Первый_HD tvg-logo="Первый канал" deinterlace=4 group-title="Эфирные каналы",Первый канал HD.
     */
    public function __construct(?string $lineStr = null)
    {
        if (null !== $lineStr) {
            $this->make($lineStr);
        }
    }

    public function __toString(): string
    {
        $attributesString = $this->getAttributesString();

        return '#EXTINF:'.$this->getDuration().('' === $attributesString ? '' : ' '.$attributesString).','.$this->getTitle();
    }

    /**
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setDuration(float $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public static function isMatch(string $lineStr): bool
    {
        return 0 === \stripos($lineStr, '#EXTINF:');
    }

    /**
     * @see http://l189-238-14.cn.ru/api-doc/m3u-extending.html
     */
    protected function make(string $lineStr): void
    {
        /*
EXTINF format:
#EXTINF:<duration> [<attributes-list>], <title>
example:
#EXTINF:-1 tvg-name=Первый_HD tvg-logo="Первый канал" deinterlace=4 group-title="Эфирные каналы",Первый канал HD
         */
        $dataLineStr = \substr($lineStr, \strlen('#EXTINF:'));
        $dataLineStr = \trim($dataLineStr);

        // Parse duration and title with regex
        \preg_match('/^(-?[\d\.]+)\s*(?:(?:[^=]+=["\'][^"\']*["\'])|(?:[^=]+=[^ ]*))*,(.*)$/', $dataLineStr, $matches);

        $this->setDuration((float) $matches[1]);
        $this->setTitle(\trim($matches[2]));

        // Attributes are remaining string after remove duration and title
        $attributes = \preg_replace('/^'.\preg_quote($matches[1], '/').'(.*)'.\preg_quote($matches[2], '/').'$/', '$1', $dataLineStr);

        $splitAttributes = \explode(' ', $attributes, 2);

        if (isset($splitAttributes[1]) && $trimmedAttributes = \trim($splitAttributes[1])) {
            $this->initAttributes($trimmedAttributes);
        }
    }
}
