<?php

namespace M3uParser;

class M3uParser
{
    use TagsManagerTrait;

    /**
     * @return M3uEntry
     */
    protected function createM3uEntry(): M3uEntry
    {
        return new M3uEntry();
    }

    /**
     * @return M3uData
     */
    protected function createM3uData(): M3uData
    {
        return new M3uData();
    }

    /**
     * Parse m3u file
     *
     * @param string $file
     * @throws Exception
     * @return M3uData entries
     */
    public function parseFile(string $file): M3uData
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
    public function parse(string $str): M3uData
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
    protected function parseLine(int &$lineNumber, array $linesStr): M3uEntry
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
    protected function removeBom(string &$str): void
    {
        if (0 === \strpos($str, "\xEF\xBB\xBF")) {
            $str = \substr($str, 3);
        }
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected function isExtM3u(string $lineStr): bool
    {
        return 0 === \stripos($lineStr, '#EXTM3U');
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected function isComment(string $lineStr): bool
    {
        $matched = false;
        foreach ($this->getTags() as $availableTag) {
            if ($availableTag::isMatch($lineStr)) {
                $matched = true;
                break;
            }
        }

        return !$matched && 0 === \strpos($lineStr, '#') && !$this->isExtM3u($lineStr);
    }
}
