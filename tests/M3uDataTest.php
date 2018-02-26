<?php
namespace M3uParser\Tests;

use M3uParser\M3uData;
use M3uParser\M3uEntry;
use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class M3uDataTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $expectedString = '#EXTM3U test-name="test-value"' . "\n";
        $expectedString .= '#EXTINF: 123 test-attr="test-attrname", extinf-title' . "\n";
        $expectedString .= '#EXTTV: hd,sd;ru;xml-tv-id;https://example.org/icon.png' . "\n";
        $expectedString .= 'test-path';


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

        $data = new M3uData();
        $data->setAttribute('test-name', 'test-value');
        $data->append($entry);

        self::assertEquals($expectedString, (string)$data);
    }
}
