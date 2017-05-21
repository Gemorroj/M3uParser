<?php

namespace M3uParser;

use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class M3uParser
{
    /**
     * Parse m3u file
     *
     * @param string $file
     * @return Data entries
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
     * @return Data entries
     */
    public function parse($str)
    {
        $this->removeBom($str);

        $data = new Data();
        $lines = \explode("\n", $str);

        for ($i = 0, $l = \count($lines); $i < $l; ++$i) {
            $lineStr = \trim($lines[$i]);
            if ('' === $lineStr || static::isComment($lineStr)) {
                continue;
            }

            if (static::isExtM3u($lineStr)) {
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
     * @return Entry
     */
    protected function parseLine(&$lineNumber, array $linesStr)
    {
        $entry = new Entry();

        for ($l = \count($linesStr); $lineNumber < $l; ++$lineNumber) {
            $nextLineStr = $linesStr[$lineNumber];
            $nextLineStr = \trim($nextLineStr);

            if ('' === $nextLineStr || static::isComment($nextLineStr) || static::isExtM3u($nextLineStr)) {
                continue;
            }

            if (static::isExtInf($nextLineStr)) {
                $entry->setExtInf(new ExtInf($nextLineStr));
                continue;
            }
            if (static::isExtTv($nextLineStr)) {
                $entry->setExtTv(new ExtTv($nextLineStr));
                continue;
            }

            $entry->setPath($nextLineStr);
            break;
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
    protected static function isExtInf($lineStr)
    {
        return '#EXTINF:' === \strtoupper(\substr($lineStr, 0, 8));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected static function isExtTv($lineStr)
    {
        return '#EXTTV:' === \strtoupper(\substr($lineStr, 0, 7));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected static function isExtM3u($lineStr)
    {
        return '#EXTM3U' === \strtoupper(\substr($lineStr, 0, 7));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected static function isComment($lineStr)
    {
        return '#' === \substr($lineStr, 0, 1) && !static::isExtInf($lineStr) && !static::isExtTv($lineStr) && !static::isExtM3u($lineStr);
    }
}
