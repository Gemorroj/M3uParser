<?php

namespace M3uParser;

/**
 * @see http://l189-238-14.cn.ru/api-doc/m3u-extending.html
 */
trait TagAttributesTrait
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * example string: tvg-ID="" tvg-name="MEDI 1 SAT" tvg-logo="" group-title="ARABIC".
     */
    public function initAttributes(string $attrString): void
    {
        $this->parseQuotedAttributes($attrString);
        $this->parseNotQuotedAttributes($attrString);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return $this
     */
    public function setAttribute(string $name, string $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    protected function getAttributesString(): string
    {
        $out = '';

        foreach ($this->getAttributes() as $name => $value) {
            $escapedValue = \addcslashes($value, '"');
            $out .= "$name=\"$escapedValue\" ";
        }

        return \rtrim($out);
    }

    private function parseQuotedAttributes(string $attrString): void
    {
        \preg_match_all('/([a-zA-Z0-9\-]+)="([^"]*)"/', $attrString, $matches, \PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->setAttribute($match[1], $match[2]);
        }
    }

    private function parseNotQuotedAttributes(string $attrString): void
    {
        \preg_match_all('/([a-zA-Z0-9\-]+)=([^ "]+)/', $attrString, $matches, \PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->setAttribute($match[1], $match[2]);
        }
    }
}
