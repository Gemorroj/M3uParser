<?php
namespace Tests\M3uParser;

use M3uParser\M3uParser;

class M3uParserTest extends \PHPUnit_Framework_TestCase
{
    protected function getFixturesDirectory()
    {
        return __DIR__ . '/../fixtures';
    }

    public function testParseFileFail()
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException('M3uParser\Exception');
        } else {
            $this->setExpectedException('M3uParser\Exception'); // for old phpunit
        }

        $m3uParser = new M3uParser();
        $m3uParser->parseFile('fake_file');
    }

    public function testParseFile1()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/1.m3u');

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(5, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('Alternative\everclear_SMFTA.mp3', $data[0]->getPath());
        self::assertNull($data[0]->getExtTv());
        self::assertInstanceOf('M3uParser\Tag\ExtInf', $data[0]->getExtInf());
        self::assertEquals('Everclear - So Much For The Afterglow', $data[0]->getExtInf()->getTitle());
        self::assertEquals(233, $data[0]->getExtInf()->getDuration());

        self::assertEquals(array(), $data[0]->getExtInf()->getAttributes());
    }

    public function testParseFile2()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/2.m3u');

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(9, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('http://nsk-ru.l.nullwave.fm:8000/club', $data[0]->getPath());
        self::assertNull($data[0]->getExtTv());
        self::assertInstanceOf('M3uParser\Tag\ExtInf', $data[0]->getExtInf());
        self::assertEquals('club', $data[0]->getExtInf()->getTitle());
        self::assertEquals(0, $data[0]->getExtInf()->getDuration());

        self::assertEquals(array(), $data[0]->getExtInf()->getAttributes());
    }

    public function testParseFile3()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/3.m3u');

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(22, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('http://nullwave.barricade.lan:8000/club', $data[0]->getPath());
        self::assertNull($data[0]->getExtTv());
        self::assertNull($data[0]->getExtInf());
    }

    public function testParseFile4()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/4.m3u');

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(7, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('http://scfire-ntc-aa07.stream.aol.com:80/stream/1048', $data[0]->getPath());
        self::assertNull($data[0]->getExtTv());
        self::assertNull($data[0]->getExtInf());
    }

    public function testParseFile5()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/5.m3u');

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(234, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('http://176.51.55.8:1234/udp/233.7.70.200:5000', $data[0]->getPath());
        self::assertNull($data[0]->getExtTv());
        self::assertInstanceOf('M3uParser\Tag\ExtInf', $data[0]->getExtInf());
        self::assertEquals('Первый канал HD', $data[0]->getExtInf()->getTitle());
        self::assertEquals(-1, $data[0]->getExtInf()->getDuration());

        self::assertEquals(array(), $data[0]->getExtInf()->getAttributes());
    }

    public function testParseFile6()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/6.m3u');

        self::assertEquals(array(
            'url-tvg' => 'http://www.teleguide.info/download/new3/jtv.zip',
            'm3uautoload' => '1',
            'deinterlace' => '8',
            'cache' => '500',
        ), $data->getAttributes());
        self::assertEquals('http://www.teleguide.info/download/new3/jtv.zip', $data->getAttribute('url-tvg'));

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(47, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('http://109.225.233.1:30000/udp/239.255.10.160:5500', $data[0]->getPath());
        self::assertNull($data[0]->getExtTv());
        self::assertInstanceOf('M3uParser\Tag\ExtInf', $data[0]->getExtInf());
        self::assertEquals('Первый канал HD', $data[0]->getExtInf()->getTitle());
        self::assertEquals(-1, $data[0]->getExtInf()->getDuration());

        self::assertEquals(array(
            'tvg-logo' => 'Первый канал',
            'group-title' => 'Эфирные каналы',
            'tvg-name' => 'Первый_HD',
            'deinterlace' => '4',
        ), $data[0]->getExtInf()->getAttributes());
    }

    public function testParseFile7()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/7.m3u');

        self::assertInstanceOf('M3uParser\Data', $data);
        self::assertCount(269, $data);

        self::assertContainsOnlyInstancesOf('M3uParser\Entry', $data);

        self::assertEquals('rtp://@232.2.201.53:5003', $data[0]->getPath());
        self::assertInstanceOf('M3uParser\Tag\ExtTv', $data[0]->getExtTv());
        self::assertEquals(array('Slovenski', 'HD'), $data[0]->getExtTv()->getTags());
        self::assertEquals('slv', $data[0]->getExtTv()->getLanguage());
        self::assertEquals('SLO1HD', $data[0]->getExtTv()->getXmlTvId());
        self::assertNull($data[0]->getExtTv()->getIconUrl());

        self::assertInstanceOf('M3uParser\Tag\ExtInf', $data[0]->getExtInf());
        self::assertEquals('TV SLO 1 HD', $data[0]->getExtInf()->getTitle());
        self::assertEquals(1, $data[0]->getExtInf()->getDuration());

        self::assertEquals(array(), $data[0]->getExtInf()->getAttributes());
    }
}
