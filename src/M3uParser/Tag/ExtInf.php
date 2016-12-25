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

namespace M3uParser\Tag;

use M3uParser\M3uParser;
use M3uParser\Tag;

class ExtInf extends Tag
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $lineStr
     * @param int $lineNumber
     * @param string[] $linesStr
     */
    public function __construct($lineStr, &$lineNumber, array $linesStr)
    {
        $this->makeData($lineStr, $lineNumber, $linesStr);
    }

    /**
     * @param string $lineStr
     * @param int $lineNumber
     * @param string[] $linesStr
     */
    protected function makeData($lineStr, &$lineNumber, array $linesStr)
    {
        $tmp = substr($lineStr, 8);

        $split = explode(',', $tmp, 2);
        if (isset($split[1])) {
            $this->setName($split[1]);
        } else {
            $this->setName($tmp);
        }

        for ($l = count($linesStr); $lineNumber < $l; ++$lineNumber) {
            $nextLineStr = $linesStr[$lineNumber];
            $nextLineStr = trim($nextLineStr);
            if ('' === $nextLineStr || M3uParser::isComment($nextLineStr)) {
                continue;
            }
            $this->setPath($nextLineStr);
            break;
        }
    }

    /**
     * @param string $name
     * @return ExtInf
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Tile file
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
