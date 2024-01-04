<?php

namespace M3uParser\Tag;

/**
 * @see https://github.com/Gemorroj/M3uParser/issues/34
 */
class ExtAlbumArtUrl implements ExtTagInterface
{
    private string $value;

    /**
     * #EXTALBUMARTURL:https://store.example.com/download/A32X5yz-1.jpg.
     */
    public function __construct(string $lineStr = null)
    {
        if (null !== $lineStr) {
            $this->make($lineStr);
        }
    }

    public function __toString(): string
    {
        return '#EXTALBUMARTURL:'.$this->getValue();
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function isMatch(string $lineStr): bool
    {
        return 0 === \stripos($lineStr, '#EXTALBUMARTURL:');
    }

    protected function make(string $lineStr): void
    {
        /*
EXTALBUMARTURL format:
#EXTALBUMARTURL:<url>
example:
#EXTALBUMARTURL:https://store.example.com/download/A32X5yz-1.jpg
         */
        $dataLineStr = \substr($lineStr, \strlen('#EXTALBUMARTURL:'));
        $dataLineStr = \trim($dataLineStr);

        $this->setValue($dataLineStr);
    }
}
