# Parser for m3u playlists.

[![Build Status](https://secure.travis-ci.org/Gemorroj/M3uParser.png?branch=master)](https://travis-ci.org/Gemorroj/M3uParser)


### Requirements:

- PHP >= 5.3


### Installation:

- Add to composer.json:

```json
{
    "require": {
        "gemorroj/m3u-parser": "dev-master"
    }
}
```
- install project:

```bash
$ composer update gemorroj/m3u-parser
```


### Example:

```php
<?php
use M3uParser\M3uParser;

$m3uParser = new M3uParser();
$data = $m3uParser->parseFile('path_to.m3u');

foreach ($data as $entry) {
    var_dump($entry);
    /*
object(M3uParser\Entry)#402 (3) {
  ["extInf":"M3uParser\Entry":private]=>
  object(M3uParser\Tag\ExtInf)#407 (2) {
    ["title":"M3uParser\Tag\ExtInf":private]=>
    string(11) "TV SLO 1 HD"
    ["duration":"M3uParser\Tag\ExtInf":private]=>
    int(1)
  }
  ["extTv":"M3uParser\Entry":private]=>
  object(M3uParser\Tag\ExtTv)#404 (4) {
    ["tags":"M3uParser\Tag\ExtTv":private]=>
    array(2) {
      [0]=>
      string(9) "Slovenski"
      [1]=>
      string(2) "HD"
    }
    ["language":"M3uParser\Tag\ExtTv":private]=>
    string(3) "slv"
    ["xmlTvId":"M3uParser\Tag\ExtTv":private]=>
    string(6) "SLO1HD"
    ["iconUrl":"M3uParser\Tag\ExtTv":private]=>
    NULL
  }
  ["path":"M3uParser\Entry":private]=>
  string(24) "rtp://@232.2.201.53:5003"
}
    */

    echo $entry->getPath() . "\n";
    if ($entry->getExtInf()) {
        echo $entry->getExtInf()->getTitle() . "\n";
        echo $entry->getExtInf()->getDuration() . "\n";
    }
    if ($entry->getExtTv()) {
        echo $entry->getExtTv()->getXmlTvId() . "\n";
        echo $entry->getExtTv()->getIconUrl() . "\n";
        echo $entry->getExtTv()->getLanguage() . "\n";
        foreach ($entry->getExtTv()->getTags() as $tag) {
            echo $tag . "\n";
        }
    }
}
```
