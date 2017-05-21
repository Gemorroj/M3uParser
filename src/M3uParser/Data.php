<?php

namespace M3uParser;

class Data extends \ArrayIterator
{
    use TagAttributesTrait;

    /**
     * @see http://l189-238-14.cn.ru/api-doc/m3u-extending.html
     * @param string $lineStr
     */
    public function makeAttributes($lineStr)
    {
        /*
         * format:
         * #EXTM3U [<attributes-list>]
         * example:
         * #EXTM3U url-tvg="http://www.teleguide.info/download/new3/jtv.zip" m3uautoload=1 deinterlace=8 cache=500
         */
        $tmp = \substr($lineStr, 7);
        $split = \explode(',', $tmp, 2);
        $splitAttributes = \explode(' ', $split[0], 2);

        if (isset($splitAttributes[1]) && \trim($splitAttributes[1])) {
            $this->initAttributes(\trim($splitAttributes[1]));
        }
    }
}
