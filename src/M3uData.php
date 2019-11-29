<?php

namespace M3uParser;

class M3uData extends \ArrayIterator
{
    use TagAttributesTrait;

    /**
     * @return string
     */
    public function __toString(): string
    {
        $out = '#EXTM3U ' . $this->getAttributesString() . "\n";

        foreach ($this as $entry) {
            $out .= $entry . "\n";
        }

        return \rtrim($out);
    }
}
