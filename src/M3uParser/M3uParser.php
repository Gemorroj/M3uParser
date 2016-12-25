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

class M3uParser
{
    /**
     * Parse m3u file
     *
     * @param string $file
     * @return Tag[]|ExtInf[]
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
     * @return Tag[]|ExtInf[]
     */
    public function parse($str)
    {
        $this->removeBom($str);

        $data = array();
        $lines = explode("\n", $str);

        for ($i = 0, $l = count($lines); $i < $l; ++$i) {
            $entry = $this->parseLine($i, $lines);
            if (null === $entry) {
                continue;
            }

            $data[] = $entry;
        }

        return $data;
    }

    /**
     * Parse one line
     *
     * @param int $lineNumber
     * @param string[] $linesStr
     * @return Tag|ExtInf|null
     */
    protected function parseLine(&$lineNumber, array $linesStr)
    {
        $lineStr = $linesStr[$lineNumber];
        $lineStr = trim($lineStr);

        if ('' === $lineStr || (self::isComment($lineStr) && !self::isExtInf($lineStr))) {
            return null;
        }

        if (self::isExtInf($lineStr)) {
            $entry = new ExtInf($lineStr, $lineNumber, $linesStr);
        } else {
            $entry = new Tag();
            $entry->setPath($lineStr);
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
    public static function isExtInf($lineStr)
    {
        return '#EXTINF:' === strtoupper(substr($lineStr, 0, 8));
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    public static function isComment($lineStr)
    {
        return '#' === substr($lineStr, 0, 1);
    }
}
