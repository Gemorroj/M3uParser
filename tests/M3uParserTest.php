<?php
namespace M3uParser\Tests;

use M3uParser\M3uParser;
use M3uParser\Exception as M3uParserException;
use M3uParser\M3uData as M3uParserData;
use M3uParser\M3uEntry as M3uParserEntry;
use M3uParser\Tag\ExtTagInterface;
use M3uParser\Tag\ExtInf;
use M3uParser\Tag\ExtTv;

class M3uParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseFileFail()
    {
        $this->expectException(M3uParserException::class);

        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $m3uParser->parseFile('fake_file');
    }

    public function testParseFileExtInf()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/extinf.m3u');

        self::assertInstanceOf(M3uParserData::class, $data);
        self::assertCount(3, $data);

        self::assertContainsOnlyInstancesOf(M3uParserEntry::class, $data);

        // basic
        /** @var M3uParserEntry $firstEntry */
        $firstEntry = $data[0];

        self::assertEquals('Alternative\everclear_SMFTA.mp3', $firstEntry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $firstEntry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtInf $extInf */
        $extInf = $extTags[0];
        self::assertInstanceOf(ExtInf::class, $extInf);

        self::assertEquals('Everclear - So Much For The Afterglow', $extInf->getTitle());
        self::assertEquals(233, $extInf->getDuration());

        self::assertEquals([], $extInf->getAttributes());


        // cyrillic
        /** @var M3uParserEntry $secondEntry */
        $secondEntry = $data[1];

        self::assertEquals('http://176.51.55.8:1234/udp/233.7.70.200:5000', $secondEntry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $secondEntry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtInf $extInf */
        $extInf = $extTags[0];
        self::assertInstanceOf(ExtInf::class, $extInf);

        self::assertEquals('Первый канал HD', $extInf->getTitle());
        self::assertEquals(-1, $extInf->getDuration());

        self::assertEquals([], $extInf->getAttributes());


        // attributes
        /** @var M3uParserEntry $thirdEntry */
        $thirdEntry = $data[2];

        self::assertEquals('http://109.225.233.1:30000/udp/239.255.10.160:5500', $thirdEntry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $thirdEntry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtInf $extInf */
        $extInf = $extTags[0];
        self::assertInstanceOf(ExtInf::class, $extInf);

        self::assertEquals('Первый канал HD', $extInf->getTitle());
        self::assertEquals(-1, $extInf->getDuration());

        self::assertEquals([
            'tvg-logo' => 'Первый канал',
            'group-title' => 'Эфирные каналы',
            'tvg-name' => 'Первый_HD',
            'deinterlace' => '4',
        ], $extInf->getAttributes());
    }


    public function testParseFileExtM3u()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/extm3u.m3u');

        self::assertEquals([
            'url-tvg' => 'http://www.teleguide.info/download/new3/jtv.zip',
            'm3uautoload' => '1',
            'deinterlace' => '8',
            'cache' => '500',
        ], $data->getAttributes());
        self::assertEquals('http://www.teleguide.info/download/new3/jtv.zip', $data->getAttribute('url-tvg'));
    }


    public function testParseFileComment()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/comment.m3u');

        /** @var M3uParserEntry $entry */
        $entry = $data[0];

        self::assertEquals('http://nullwave.barricade.lan:8000/club', $entry->getPath());
        self::assertEmpty($entry->getExtTags());
    }

    public function testParseFileNoTags()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/notags.m3u');

        /** @var M3uParserEntry $entry */
        $entry = $data[0];

        self::assertEquals('http://scfire-ntc-aa07.stream.aol.com:80/stream/1048', $entry->getPath());
        self::assertEmpty($entry->getExtTags());
    }


    public function testParseFileExtTv()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/exttv.m3u');

        /** @var M3uParserEntry $entry */
        $entry = $data[0];

        self::assertEquals('rtp://@232.2.201.53:5003', $entry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $entry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtTv $extTv */
        $extTv = $extTags[0];
        self::assertInstanceOf(ExtTv::class, $extTv);

        self::assertEquals(['Slovenski', 'HD'], $extTv->getTags());
        self::assertEquals('slv', $extTv->getLanguage());
        self::assertEquals('SLO1HD', $extTv->getXmlTvId());
        self::assertNull($extTv->getIconUrl());
    }

    public function testParseFileCombinedExtTags()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/combined.m3u');

        /** @var M3uParserEntry $entry */
        $entry = $data[0];

        self::assertEquals('rtp://@232.2.201.53:5003', $entry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $entry->getExtTags();
        self::assertCount(2, $extTags);

        self::assertContainsOnlyInstancesOf(ExtTagInterface::class, $extTags);
    }

    public function testParseFileExtCustomTag()
    {
        $m3uParser = new M3uParser();
        $m3uParser->addTag(ExtCustomTag::class);
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/customtag.m3u');

        /** @var M3uParserEntry $entry */
        $entry = $data[0];

        self::assertEquals('http://nullwave.barricade.lan:8000/club', $entry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $entry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtCustomTag $extCustomTag */
        $extCustomTag = $extTags[0];
        self::assertInstanceOf(ExtCustomTag::class, $extCustomTag);

        self::assertEquals('123', $extCustomTag->getData());
    }

    public function testPr14()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/fixtures/github/pr14.m3u');
        self::assertCount(3, $data);

        foreach ($data as $entry) {
            self::assertInstanceOf(M3uParserEntry::class, $entry);
        }

        self::assertEquals('http://srv.test-channel.vip:8880/veLMOIkQ2l/10634', $data[2]->getPath());
    }
}
