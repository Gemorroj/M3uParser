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
     * @param string $file
     * @return Entry[]
     * @throws Exception
     */
    public function parseFile($file)
    {
        $str = @file_get_contents($file);
        if (false === $str) {
            throw new Exception('Can\'t get file.');
        }

        return $this->parse($str);
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


    /**
     * @param string $str
     * @return Entry[]
     */
    public function parse($str)
    {
        $this->removeBom($str);

        $data = array();
        $lines = explode("\n", $str);

        while (list(, $line) = each($lines)) {
            $line = trim($line);
            if ($line === '' || strtoupper($line) === '#EXTM3U') {
                continue;
            }

            $entry = new Entry();

            if (strtoupper(substr($line, 0, 8)) === '#EXTINF:') {
                $tmp = substr($line, 8);

                $split = explode(',', $tmp, 2);
                if (isset($split[1]) && preg_match('/^\-*[0-9]+$/', $split[0])) {
                    $entry->setLength($split[0]);
                    $entry->setName($split[1]);
                } else {
                    $entry->setName($tmp);
                }

                $path = $this->eachPath($lines);
                if ($path !== null) {
                    $entry->setPath($path);
                }

            } else if (substr($line, 0, 1) === '#') {
                $tmp = trim(substr($line, 1));
                if ($tmp !== '') {
                    $entry->setName($tmp);
                }

                $path = $this->eachPath($lines);
                if ($path !== null) {
                    $entry->setPath($path);
                }
            } else {
                $entry->setPath($line);
            }

            $data[] = $entry;
        }

        return $data;
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
}
