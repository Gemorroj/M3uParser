<?php
/**
 *
 * This software is distributed under the GNU GPL v3.0 license.
 *
 * @author    Gemorroj
 * @copyright 2015 http://wapinet.ru
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/Gemorroj/Archive7z
 * @version   0.1
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

        while (list(, $line) = each($lines)) {

            $entry = $this->parseLine($line, $lines);
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
     * @param string $lineStr
     * @param string[] $linesStr
     * @return Entry|null
     */
    protected function parseLine($lineStr, array $linesStr)
    {
        $lineStr = trim($lineStr);
        if ($lineStr === '' || strtoupper(substr($lineStr, 0, 7)) === '#EXTM3U') {
            return null;
        }

        $entry = new Entry();

        if (strtoupper(substr($lineStr, 0, 8)) === '#EXTINF:') {
            $tmp = substr($lineStr, 8);

            $split = explode(',', $tmp, 2);
            if (isset($split[1])) {
                $entry->setName($split[1]);
            } else {
                $entry->setName($tmp);
            }

            $path = $this->eachPath($linesStr);
            if ($path !== null) {
                $entry->setPath($path);
            }

        } else if (substr($lineStr, 0, 1) === '#') {
            $tmp = trim(substr($lineStr, 1));
            if ($tmp !== '') {
                $entry->setName($tmp);
            }

            $path = $this->eachPath($linesStr);
            if ($path !== null) {
                $entry->setPath($path);
            }
        } else {
            $entry->setPath($lineStr);
        }

        return $entry;
    }


    /**
     * @param array $lines
     * @return null|string
     */
    protected function eachPath(array &$lines)
    {
        while (list(, $line) = each($lines)) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            return $line;
        }

        return null;
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
