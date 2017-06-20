<?php
namespace M3uParser\Tests;

use M3uParser\Data;
use M3uParser\Entry;
use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class DataTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $expectedString = '#EXTM3U test-name="test-value"' . "\n";
        $expectedString .= '#EXTINF: 123 test-attr="test-attrname", extinf-title' . "\n";
        $expectedString .= '#EXTTV: hd,sd;ru;xml-tv-id;https://example.org/icon.png' . "\n";
        $expectedString .= 'test-path';


        $entry = new Entry();
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

        $data = new Data();
        $data->setAttribute('test-name', 'test-value');
        $data->append($entry);

        self::assertEquals($expectedString, (string)$data);
    }
}
