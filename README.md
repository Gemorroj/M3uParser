# Original Project

The original project is from gemorroj but he didn't accept my merge requst, so, I made my changes and create a new repo with my changes.

- My big update is accept a patch each line of the m3u

# Parser/Generator m3u playlists

[![License](https://poser.pugx.org/tschope/m3u-parser/license)](https://packagist.org/packages/tschope/m3u-parser)
[![Latest Stable Version](https://poser.pugx.org/tschope/m3u-parser/v/stable)](https://packagist.org/packages/tschope/m3u-parser)
[![Build Status Travis](https://secure.travis-ci.org/tschope/M3uParser.png?branch=master)](https://travis-ci.org/tschope/M3uParser)
[![Build Status AppVeyor](https://ci.appveyor.com/api/projects/status/elqxwcdihjhu0gvp)](https://ci.appveyor.com/project/tschope/m3uparser)


### Requirements:

- PHP >= 5.6


### Installation:
```bash
composer require tschope/m3u-parser
```


### Example parser:

```php
<?php
use M3uParser\M3uParser;

$m3uParser = new M3uParser();
$m3uParser->addDefaultTags();
$data = $m3uParser->parseFile('path_to_file.m3u');

print_r($data->getAttributes());
/*
Array
(
    [url-tvg] => http://www.teleguide.info/download/new3/jtv.zip
    [m3uautoload] => 1
    [deinterlace] => 8
    [cache] => 500
)
*/

/** @var \M3uParser\M3uEntry $entry */
foreach ($data as $entry) {
    print_r($entry);
    /*
        M3uParser\M3uEntry Object
        (
            [lineDelimiter:protected] =>

            [extTags:M3uParser\M3uEntry:private] => Array
                (
                    [0] => M3uParser\Tag\ExtInf Object
                        (
                            [title:M3uParser\Tag\ExtInf:private] => TV SLO 1 HD
                            [duration:M3uParser\Tag\ExtInf:private] => 1
                            [attributes:M3uParser\Tag\ExtInf:private] => Array
                                (
                                )

                        )

                    [1] => M3uParser\Tag\ExtTv Object
                        (
                            [tags:M3uParser\Tag\ExtTv:private] => Array
                                (
                                    [0] => Slovenski
                                    [1] => HD
                                )

                            [language:M3uParser\Tag\ExtTv:private] => slv
                            [xmlTvId:M3uParser\Tag\ExtTv:private] => SLO1HD
                            [iconUrl:M3uParser\Tag\ExtTv:private] =>
                        )

                )

            [path:M3uParser\M3uEntry:private] => rtp://@232.2.201.53:5003
        )
    */

    echo $entry->getPath() . "\n";

    foreach ($entry->getExtTags() as $extTag) {
        switch ($extTag) {
            case $extTag instanceof \M3uParser\Tag\ExtInf: // If EXTINF tag
                echo $extTag->getTitle() . "\n";
                echo $extTag->getDuration() . "\n";

                if ($extTag->hasAttribute('tvg-name')) { // If tvg-name attribute in EXTINF tag
                    echo $extTag->getAttribute('tvg-name') . "\n";
                }
                break;

            case $extTag instanceof \M3uParser\Tag\ExtTv: // If EXTTV tag
                echo $extTag->getXmlTvId() . "\n";
                echo $extTag->getIconUrl() . "\n";
                echo $extTag->getLanguage() . "\n";
                foreach ($extTag->getTags() as $tag) {
                    echo $tag . "\n";
                }
                break;
        }
    }
}
```

### Example generator:

```php
<?php
use M3uParser\M3uData;
use M3uParser\M3uEntry;
use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

$entry = new M3uEntry();
$entry->setPath('test-path');
$entry->addExtTag(
    (new ExtInf())
        ->setDuration(123)
        ->setTitle('extinf-title')
        ->setPatch('http://srv.test-tvchannel.vip:8880/101525')
        ->setAttribute('test-attr', 'test-attrname')
);
$entry->addExtTag(
    (new ExtTv())
        ->setIconUrl('https://example.org/icon.png')
        ->setLanguage('ru')
        ->setXmlTvId('xml-tv-id')
        ->setPatch('http://srv.test-tvchannel.vip:8880/101525')
        ->setTags(['hd', 'sd'])
);

$data = new M3uData();
$data->setAttribute('test-name', 'test-value');
$data->append($entry);

echo $data;
/*
#EXTM3U test-name="test-value"
#EXTINF: 123 test-attr="test-attrname", extinf-title
http://srv.test-tvchannel.vip:8880/101525
#EXTTV: hd,sd;ru;xml-tv-id;https://example.org/icon.png
http://srv.test-tvchannel.vip:8880/101525
test-path
*/
```

### Example custom tag:
```
#EXTM3U
#EXTCUSTOMTAG:123
http://nullwave.barricade.lan:8000/club
```

implement `ExtTagInterface` interface
```php
<?php

use M3uParser\M3uParser;
use M3uParser\Tag\ExtTagInterface;

class ExtCustomTag implements ExtTagInterface
{
    /**
     * @var string
     */
    private $data;

    /**
     * #EXTCUSTOMTAG:data
     * @param string $lineStr
     */
    public function __construct($lineStr = null)
    {
        if (null !== $lineStr) {
            $this->makeData($lineStr);
        }
    }

    /**
     * @param string $lineStr
     */
    protected function makeData($lineStr)
    {
        /**
         * EXTCUSTOMTAG format:
         * #EXTCUSTOMTAG:data
         * example:
         * #EXTCUSTOMTAG:123
         */

        $data = \substr($lineStr, \strlen('#EXTCUSTOMTAG:'));

        $this->setData(\trim($data));
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '#EXTCUSTOMTAG: ' . $this->getData();
    }

    /**
     * @param string $lineStr
     * @return bool
     */
    public static function isMatch($lineStr)
    {
        return '#EXTCUSTOMTAG:' === \strtoupper(\substr($lineStr, 0, \strlen('#EXTCUSTOMTAG:')));
    }
}

$m3uParser = new M3uParser();
// add custom tag
$m3uParser->addTag(ExtCustomTag::class);
$data = $m3uParser->parseFile('path_to_file.m3u');

print_r($data);
/*
M3uParser\M3uData Object
(
    [attributes:M3uParser\M3uData:private] => Array
        (
        )

    [storage:ArrayIterator:private] => Array
        (
            [0] => M3uParser\M3uEntry Object
                (
                    [lineDelimiter:protected] =>

                    [extTags:M3uParser\M3uEntry:private] => Array
                        (
                            [0] => M3uParser\Tests\ExtCustomTag Object
                                (
                                    [data:M3uParser\Tests\ExtCustomTag:private] => 123
                                )

                        )
 
                    [path:M3uParser\M3uEntry:private] => http://nullwave.barricade.lan:8000/club
                )
 
        )

)
*/
 ```
