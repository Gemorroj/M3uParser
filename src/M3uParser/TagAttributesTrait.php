<?php

namespace M3uParser;


trait TagAttributesTrait
{
    private $attributes = array();

    /**
     * example string: tvg-ID="" tvg-name="MEDI 1 SAT" tvg-logo="" group-title="ARABIC"
     *
     * @param string $attrString
     */
    public function initAttributes($attrString)
    {
        $this->parseQuotedAttributes($attrString);
        $this->parseNotQuotedAttributes($attrString);
    }

    /**
     * @param string $attrString
     */
    private function parseQuotedAttributes($attrString)
    {
        \preg_match_all('/([a-zA-Z0-9\-]+)="([^"]*)"/', $attrString, $matches, \PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->setAttribute($match[1], $match[2]);
        }
    }


    /**
     * @param string $attrString
     */
    private function parseNotQuotedAttributes($attrString)
    {
        \preg_match_all('/([a-zA-Z0-9\-]+)=([^ "]+)/', $attrString, $matches, \PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->setAttribute($match[1], $match[2]);
        }
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}
