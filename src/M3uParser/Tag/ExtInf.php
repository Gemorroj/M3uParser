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


class ExtInf
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $lineStr
     */
    public function __construct($lineStr)
    {
        $this->makeData($lineStr);
    }

    /**
     * @param string $lineStr
     */
    protected function makeData($lineStr)
    {
        $tmp = substr($lineStr, 8);

        $split = explode(',', $tmp, 2);
        if (isset($split[1])) {
            $this->setName($split[1]);
        } else {
            $this->setName($tmp);
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
