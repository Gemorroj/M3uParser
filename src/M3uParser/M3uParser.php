<?php
/**
 *
 * This software is distributed under the GNU GPL v3.0 license.
 *
 * @author    Gemorroj
 * @copyright 2015 http://wapinet.ru
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/Gemorroj/Archive7z
 * @version   0.2
 *
 */

namespace M3uParser;

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
     * @return Entry|null
     */
    protected function parseLine(&$lineNumber, array $linesStr)
    {
        $lineStr = $linesStr[$lineNumber];
        $lineStr = trim($lineStr);

        if ($lineStr === '' || ($this->isComment($lineStr) && !$this->isExtInf($lineStr))) {
            return null;
        }

        if ($this->isExtInf($lineStr)) {
            $entry = $this->makeExtEntry($lineStr, $lineNumber, $linesStr);
        } else {
            $entry = new Entry();
            $entry->setPath($lineStr);
        }

        return $entry;
    }


    /**
     * @param string $lineStr
     * @param int $lineNumber
     * @param array $linesStr
     * @return Entry
     */
    protected function makeExtEntry($lineStr, &$lineNumber, array $linesStr)
    {
        $entry = new Entry();
        $tmp = substr($lineStr, 8);

        $split = explode(',', $tmp, 2);
        if (isset($split[1])) {
            $entry->setName($split[1]);
        } else {
            $entry->setName($tmp);
        }

        for ($l = count($linesStr); $lineNumber < $l; ++$lineNumber) {
            $nextLineStr = $linesStr[$lineNumber];
            $nextLineStr = trim($nextLineStr);
            if ($nextLineStr === '' || $this->isComment($nextLineStr)) {
                continue;
            }
            $entry->setPath($nextLineStr);
            break;
        }

        return $entry;
    }


    /**
     * @param string $lineStr
     * @return bool
     */
    protected function isExtInf($lineStr)
    {
        return strtoupper(substr($lineStr, 0, 8)) === '#EXTINF:';
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    protected function isComment($lineStr)
    {
        return substr($lineStr, 0, 1) === '#';
    }


    /**
     * @param string $str
     */
    protected function removeBom(&$str)
    {
        if (substr($str, 0, 3) === "\xEF\xBB\xBF") {
            $str = substr($str, 3);
        }
    }
}
