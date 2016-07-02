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

class Entry
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     * @return Entry
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Path to file
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * @param string $name
     * @return Entry
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Tile file
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
