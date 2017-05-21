<?php

namespace M3uParser;

class Data extends \ArrayIterator
{
    use TagAttributesTrait;

    /**
     * @return string
     */
    public function __toString()
    {
        $out = '#EXTM3U ' . $this->getAttributesString() . "\n";

        foreach ($this as $entry) {
            $out .= $entry . "\n";
        }

        return \rtrim($out);
    }
}
