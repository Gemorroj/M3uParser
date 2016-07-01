# Работа с m3u файлами.

[![Build Status](https://secure.travis-ci.org/Gemorroj/M3uParser.png?branch=master)](https://travis-ci.org/Gemorroj/M3uParser)


### Требования:

- PHP >= 5.3


### Установка через composer:

- Добавьте проект в ваш файл composer.json:

```json
{
    "require": {
        "gemorroj/m3u-parser": "dev-master"
    }
}
```
- Установите проект:

```bash
$ php composer.phar update gemorroj/m3u-parser
```


### Пример работы:

```php
<?php
$obj = new M3uParser\M3uParser();
$data = $obj->parseFile('path_to.m3u');

foreach ($data as $entry) {
    var_dump($entry);
}
```
