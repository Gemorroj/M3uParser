<?php

namespace M3uParser\Tests;

use M3uParser\Tag\ExtTagInterface;

class ExtCustomTag implements ExtTagInterface
{
    /**
     * @var string
     */
    private $data;

    /**
     * #EXTCUSTOMTAG:data
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
     */
    protected function makeData($lineStr)
    {
        /*
EXTCUSTOMTAG format:
#EXTCUSTOMTAG:data
example:
#EXTCUSTOMTAG:123
         */

        $data = \substr($lineStr, \strlen('#EXTCUSTOMTAG:'));

        $this->setData(\trim($data));
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '#EXTCUSTOMTAG: ' . $this->getData();
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    public static function isMatch($lineStr)
    {
        return '#EXTCUSTOMTAG:' === \strtoupper(\substr($lineStr, 0, \strlen('#EXTCUSTOMTAG:')));
    }
}
