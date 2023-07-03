<?php

namespace M3uParser\Tag;

interface ExtTagInterface extends \Stringable
{
    public function __construct(string $lineStr = null);

    public static function isMatch(string $lineStr): bool;
}
