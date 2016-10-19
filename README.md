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
    object(M3uParser\Entry)#2 (2) {
      ["name":"M3uParser\Entry":private]=>
      string(37) "Everclear - So Much For The Afterglow"
      ["path":"M3uParser\Entry":private]=>
      string(31) "Alternative\everclear_SMFTA.mp3"
    }
    */

    echo $entry->getPath() . "\n";
    echo $entry->getName() . "\n";
}
```
