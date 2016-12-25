<?php
/**
 *
 * This software is distributed under the GNU GPL v3.0 license.
 *
 * @author    Gemorroj
 * @copyright 2015 http://wapinet.ru
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/Gemorroj/M3uParser
 *
 */

namespace M3uParser;

use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class M3uParser
{
    /**
     * Parse m3u file
     *
     * @param string $file
     * @return Entry[]
     * @throws Exception
     */
    public function parseFile($file)
    {
        $str = @file_get_contents($file);
        if (false === $str) {
            throw new Exception('Can\'t read file.');
        }

        return $this->parse($str);
    }

    /**
     * Parse m3u string
     *
     * @param string $str
     * @return Entry[]
     */
    public function parse($str)
    {
        $this->removeBom($str);

        $data = array();
        $lines = explode("\n", $str);

        for ($i = 0, $l = count($lines); $i < $l; $i++) {
            $lineStr = trim($lines[$i]);
            if ('' === $lineStr || self::isComment($lineStr)) {
                continue;
            }

            $data[] = $this->parseLine($i, $lines);
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

        for ($l = count($linesStr); $lineNumber < $l; $lineNumber++) {
            $nextLineStr = $linesStr[$lineNumber];
            $nextLineStr = trim($nextLineStr);
            if ('' === $nextLineStr || self::isComment($nextLineStr)) {
                continue;
            }

            if (self::isExtInf($nextLineStr)) {
                $entry->setExtInf(new ExtInf($nextLineStr));
                continue;
            }
            if (self::isExtTv($nextLineStr)) {
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
        if ("\xEF\xBB\xBF" === substr($str, 0, 3)) {
            $str = substr($str, 3);
        }
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected static function isExtInf($lineStr)
    {
        return '#EXTINF:' === strtoupper(substr($lineStr, 0, 8));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected static function isExtTv($lineStr)
    {
        return '#EXTTV:' === strtoupper(substr($lineStr, 0, 7));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected static function isComment($lineStr)
    {
        return '#' === substr($lineStr, 0, 1) && !self::isExtInf($lineStr) && !self::isExtTv($lineStr);
    }
}
