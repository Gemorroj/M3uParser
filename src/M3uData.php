<?php

namespace M3uParser;

/** @extends \ArrayIterator<int, M3uEntry> */
class M3uData extends \ArrayIterator
{
    use TagAttributesTrait;

    public function __toString(): string
    {
        $out = \rtrim('#EXTM3U '.$this->getAttributesString())."\n";

        foreach ($this as $entry) {
            $out .= $entry."\n";
        }

        return \rtrim($out);
    }
}
