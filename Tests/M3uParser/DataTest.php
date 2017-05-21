<?php
namespace Tests\M3uParser;

use M3uParser\Data;
use M3uParser\Entry;
use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class DataTest extends \PHPUnit_Framework_TestCase
{
    protected function getFixturesDirectory()
    {
        return __DIR__ . '/../fixtures';
    }

    public function testToString()
    {
        $expectedString = '#EXTM3U test-name="test-value"' . "\n";
        $expectedString .= '#EXTINF: 123 test-attr="test-attrname", extinf-title' . "\n";
        $expectedString .= '#EXTTV: hd,sd;ru;xml-tv-id;https://example.org/icon.png' . "\n";
        $expectedString .= 'test-path';

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

        self::assertEquals($expectedString, (string)$data);
    }
}
