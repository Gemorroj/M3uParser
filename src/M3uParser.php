<?php

namespace M3uParser;

class M3uParser
{
    use TagsManagerTrait;

    /**
     * @return M3uEntry
     */
    protected function createM3uEntry()
    {
        return new M3uEntry();
    }

    /**
     * @return M3uData
     */
    protected function createM3uData()
    {
        return new M3uData();
    }

    /**
     * Parse m3u file
     *
     * @param string $file
     * @return M3uData entries
     * @throws Exception
     */
    public function parseFile($file)
    {
        $str = @\file_get_contents($file);
        if (false === $str) {
            throw new Exception('Can\'t read file.');
        }

        return $this->parse($str);
    }

    /**
     * Parse m3u string
     *
     * @param string $str
     * @return M3uData entries
     */
    public function parse($str)
    {
        $this->removeBom($str);

        $data = $this->createM3uData();
        $lines = \explode("\n", $str);

        for ($i = 0, $l = \count($lines); $i < $l; ++$i) {
            $lineStr = \trim($lines[$i]);
            if ('' === $lineStr || $this->isComment($lineStr)) {
                continue;
            }

            if ($this->isExtM3u($lineStr)) {
                $tmp = \trim(\substr($lineStr, 7));
                if ($tmp) {
                    $data->initAttributes($tmp);
                }
                continue;
            }

            $data->append($this->parseLine($i, $lines));
        }

        return $data;
    }

    /**
     * Parse one line
     *
     * @param int $lineNumber
     * @param string[] $linesStr
     * @return M3uEntry
     */
    protected function parseLine(&$lineNumber, array $linesStr)
    {
        $entry = $this->createM3uEntry();

        for ($l = \count($linesStr); $lineNumber < $l; ++$lineNumber) {
            $nextLineStr = $linesStr[$lineNumber];
            $nextLineStr = \trim($nextLineStr);

            if ('' === $nextLineStr || $this->isComment($nextLineStr) || $this->isExtM3u($nextLineStr)) {
                continue;
            }

            $matched = false;
            foreach ($this->getTags() as $availableTag) {
                if ($availableTag::isMatch($nextLineStr)) {
                    $matched = true;
                    $entry->addExtTag(new $availableTag($nextLineStr));
                    break;
                }
            }

            if (!$matched) {
                $entry->setPath($nextLineStr);
                break;
            }
        }

        return $entry;
    }

    /**
     * @param string $str
     */
    protected function removeBom(&$str)
    {
        if ("\xEF\xBB\xBF" === \substr($str, 0, 3)) {
            $str = \substr($str, 3);
        }
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected function isExtM3u($lineStr)
    {
        return '#EXTM3U' === \strtoupper(\substr($lineStr, 0, 7));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected function isComment($lineStr)
    {
        $matched = false;
        foreach ($this->getTags() as $availableTag) {
            if ($availableTag::isMatch($lineStr)) {
                $matched = true;
                break;
            }
        }

        return '#' === \substr($lineStr, 0, 1) && !$matched && !static::isExtM3u($lineStr);
    }
}
