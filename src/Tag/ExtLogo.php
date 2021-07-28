<?php

namespace M3uParser\Tag;

/**
 * @see https://github.com/Gemorroj/M3uParser/issues/20
 */
class ExtLogo implements ExtTagInterface
{
    /**
     * @var string
     */
    private $logo;

    /**
     * #EXTLOGO:http://cdn1.siol.tv/logo/93x78/slo2.png.
     */
    public function __construct(?string $lineStr = null)
    {
        if (null !== $lineStr) {
            $this->makeData($lineStr);
        }
    }

    public function __toString(): string
    {
        return '#EXTLOGO:'.$this->getLogo();
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @return $this
     */
    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public static function isMatch(string $lineStr): bool
    {
        return 0 === \stripos($lineStr, '#EXTLOGO:');
    }

    protected function makeData(string $lineStr): void
    {
        /*
EXTLOGO format:
#EXTLOGO:logo
example:
#EXTLOGO:http://cdn1.siol.tv/logo/93x78/slo2.png
         */

        $tmp = \substr($lineStr, \strlen('#EXTLOGO:'));
        $logo = \trim($tmp);

        $this->setLogo($logo);
    }
}
