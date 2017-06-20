<?php

namespace M3uParser\Tag;


interface ExtTagInterface
{
    /**
     * @param string|null $lineStr
     */
    public function __construct($lineStr = null);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param string $lineStr
     * @return bool
     */
    public static function isMatch($lineStr);
}
