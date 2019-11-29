<?php

namespace M3uParser\Tag;

interface ExtTagInterface
{
    public function __construct(?string $lineStr = null);

    public function __toString(): string;

    public static function isMatch(string $lineStr): bool;
}
