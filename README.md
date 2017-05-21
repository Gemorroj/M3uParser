# Parser/Generator m3u playlists.

[![Build Status](https://secure.travis-ci.org/Gemorroj/M3uParser.png?branch=master)](https://travis-ci.org/Gemorroj/M3uParser)


### Requirements:

- PHP >= 5.4


### Installation:

- Add to composer.json:

```json
{
    "require": {
        "gemorroj/m3u-parser": "*"
    }
}
```
- install project:

```bash
$ composer update gemorroj/m3u-parser
```


### Example parser:

```php
<?php
use M3uParser\M3uParser;

$m3uParser = new M3uParser();
$data = $m3uParser->parseFile('path_to.m3u');

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

foreach ($data as $entry) {
    print_r($entry);
    /*
M3uParser\Entry Object
(
    [extInf:M3uParser\Entry:private] => M3uParser\Tag\ExtInf Object
        (
            [title:M3uParser\Tag\ExtInf:private] => TV SLO 1 HD
            [duration:M3uParser\Tag\ExtInf:private] => 1
            [attributes:M3uParser\Tag\ExtInf:private] => Array
                (
                    [tvg-logo] => Первый канал
                    [group-title] => Эфирные каналы
                    [tvg-name] => Первый_HD
                    [deinterlace] => 4
                )

        )

    [extTv:M3uParser\Entry:private] => M3uParser\Tag\ExtTv Object
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

    [path:M3uParser\Entry:private] => rtp://@232.2.201.53:5003
)
    */

    echo $entry->getPath() . "\n";

    if ($entry->getExtInf()) { // If EXTINF tag
        echo $entry->getExtInf()->getTitle() . "\n";
        echo $entry->getExtInf()->getDuration() . "\n";

        if ($entry->getExtInf()->getAttribute('tvg-name')) { // If tvg-name attribute in EXTINF tag
            echo $entry->getExtInf()->getAttribute('tvg-name') . "\n";
        }
    }

    if ($entry->getExtTv()) { // If EXTTV tag
        echo $entry->getExtTv()->getXmlTvId() . "\n";
        echo $entry->getExtTv()->getIconUrl() . "\n";
        echo $entry->getExtTv()->getLanguage() . "\n";
        foreach ($entry->getExtTv()->getTags() as $tag) {
            echo $tag . "\n";
        }
    }
}
```

### Example generator:

```php
<?php
use M3uParser\Data;
use M3uParser\Entry;
use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

$data = new Data();
$data->setAttribute('test-name', 'test-value');
$data->append((new Entry())->setExtInf(
    (new ExtInf())
        ->setDuration(123)
        ->setTitle('extinf-title')
        ->setAttribute('test-attr', 'test-attrname')
));
$data->append((new Entry())->setExtTv(
    (new ExtTv())
        ->setIconUrl('https://example.org/icon.png')
        ->setLanguage('ru')
        ->setXmlTvId('xml-tv-id')
        ->setTags(array('hd', 'sd'))
));
$data->append((new Entry())->setPath('test-path'));

echo $data;
/*
#EXTM3U test-name="test-value"
#EXTINF: 123 test-attr="test-attrname", extinf-title
#EXTTV: hd,sd;ru;xml-tv-id;https://example.org/icon.png
test-path
*/
```
