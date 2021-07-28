# Parser/Generator m3u playlists

[![License](https://poser.pugx.org/gemorroj/m3u-parser/license)](https://packagist.org/packages/gemorroj/m3u-parser)
[![Latest Stable Version](https://poser.pugx.org/gemorroj/m3u-parser/v/stable)](https://packagist.org/packages/gemorroj/m3u-parser)
[![Continuous Integration](https://github.com/Gemorroj/M3uParser/workflows/Continuous%20Integration/badge.svg?branch=master)](https://github.com/Gemorroj/M3uParser/actions?query=workflow%3A%22Continuous+Integration%22)


### Requirements:

- PHP >= 7.3


### Installation:
```bash
composer require gemorroj/m3u-parser
```


### Example parser:

```php
<?php
use M3uParser\M3uParser;

$m3uParser = new M3uParser();
$m3uParser->addDefaultTags();
$data = $m3uParser->parseFile('path_to_file.m3u');
// or $data = $m3uParser->parse('playlist content');

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

            case $extTag instanceof \M3uParser\Tag\ExtLogo: // If EXTLOGO tag
                echo $extTag->getLogo() . "\n";
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
use M3uParser\Tag\ExtLogo;

$entry = new M3uEntry();
$entry->setPath('test-path');
$entry->addExtTag(
    (new ExtInf())
        ->setDuration(123)
        ->setTitle('extinf-title')
        ->setAttribute('test-attr', 'test-attrname')
);
$entry->addExtTag(
    (new ExtTv())
        ->setIconUrl('https://example.org/icon.png')
        ->setLanguage('ru')
        ->setXmlTvId('xml-tv-id')
        ->setTags(['hd', 'sd'])
);
$entry->addExtTag(
    (new ExtLogo())
        ->setLogo('https://example.org/logo.png')
);

$data = new M3uData();
$data->setAttribute('test-name', 'test-value');
$data->append($entry);

echo $data;
/*
#EXTM3U test-name="test-value"
#EXTINF: 123 test-attr="test-attrname", extinf-title
#EXTTV: hd,sd;ru;xml-tv-id;https://example.org/icon.png
#EXTLOGO: https://example.org/logo.png
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

// create custom tag
class ExtCustomTag implements ExtTagInterface
{
    /**
     * @var string
     */
    private $data;

    /**
     * #EXTCUSTOMTAG:data
     */
    public function __construct(?string $lineStr = null)
    {
        if (null !== $lineStr) {
            $this->makeData($lineStr);
        }
    }

    protected function makeData(string $lineStr): void
    {
        /*
EXTCUSTOMTAG format:
#EXTCUSTOMTAG:data
example:
#EXTCUSTOMTAG:123
         */

        $data = \substr($lineStr, \strlen('#EXTCUSTOMTAG:'));

        $this->setData(\trim($data));
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function __toString(): string
    {
        return '#EXTCUSTOMTAG:' . $this->getData();
    }

    public static function isMatch(string $lineStr): bool
    {
        return 0 === \stripos($lineStr, '#EXTCUSTOMTAG:');
    }
}

// use custom tag
$m3uParser = new M3uParser();
$m3uParser->addTag(ExtCustomTag::class); // add custom tag
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
