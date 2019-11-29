<?php

namespace M3uParser;

trait TagAttributesTrait
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * example string: tvg-ID="" tvg-name="MEDI 1 SAT" tvg-logo="" group-title="ARABIC"
     *
     * @param string $attrString
     */
    public function initAttributes(string $attrString): void
    {
        $this->parseQuotedAttributes($attrString);
        $this->parseNotQuotedAttributes($attrString);
    }

    /**
     * @param string $attrString
     */
    private function parseQuotedAttributes(string $attrString): void
    {
        \preg_match_all('/([a-zA-Z0-9\-]+)="([^"]*)"/', $attrString, $matches, \PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->setAttribute($match[1], $match[2]);
        }
    }


    /**
     * @param string $attrString
     */
    private function parseNotQuotedAttributes(string $attrString): void
    {
        \preg_match_all('/([a-zA-Z0-9\-]+)=([^ "]+)/', $attrString, $matches, \PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->setAttribute($match[1], $match[2]);
        }
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setAttribute(string $name, string $value): self
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * @return string
     */
    protected function getAttributesString(): string
    {
        $out = '';

        foreach ($this->getAttributes() as $name => $value) {
            $out .= $name . '="' . $value . '" '; //todo: quote
        }

        return \rtrim($out);
    }
}
