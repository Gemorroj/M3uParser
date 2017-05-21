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
        $this->attributes = array_merge(
            $this->parseQuotedAttributes($attrString),
            $this->parseNotQuotedAttributes($attrString)
        );
    }

    /**
     * @param string $attrString
     * @return array
     */
    private function parseQuotedAttributes($attrString)
    {
        preg_match_all('/([a-zA-Z0-9\-]+)="([^"]*)"/', $attrString, $matches, PREG_SET_ORDER);

        $result = array();
        foreach ($matches as $match) {
            $result[$match[1]] = $match[2];
        }

        return $result;
    }


    /**
     * @param string $attrString
     * @return array
     */
    private function parseNotQuotedAttributes($attrString)
    {
        preg_match_all('/([a-zA-Z0-9\-]+)=([^ "]+)/', $attrString, $matches, PREG_SET_ORDER);

        $result = array();
        foreach ($matches as $match) {
            $result[$match[1]] = $match[2];
        }

        return $result;
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
}
