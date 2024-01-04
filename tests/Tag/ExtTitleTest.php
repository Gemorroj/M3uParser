<?php

namespace M3uParser\Tests\Tag;

use M3uParser\M3uData;
use M3uParser\M3uEntry;
use M3uParser\M3uParser;
use M3uParser\Tag\ExtTagInterface;
use M3uParser\Tag\ExtTitle;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ExtTitleTest extends TestCase
{
    public function testParseExtTitle(): void
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__.'/../fixtures/exttitle.m3u');

        /** @var M3uEntry $entry */
        $entry = $data[0];

        self::assertEquals('rtp://@127.0.0.1:5003', $entry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $entry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtTitle $extTitle */
        $extTitle = $extTags[0];
        self::assertInstanceOf(ExtTitle::class, $extTitle);

        self::assertEquals('Rock music', $extTitle->getValue());
    }

    public function testGenerateExtTitle(): void
    {
        $expectedString = '#EXTM3U'."\n";
        $expectedString .= '#EXTTITLE:Rock music'."\n";
        $expectedString .= 'test-path';

        $entry = new M3uEntry();
        $entry->setPath('test-path');
        $entry->addExtTag(
            (new ExtTitle())
                ->setValue('Rock music')
        );

        $data = new M3uData();
        $data->append($entry);

        self::assertEquals($expectedString, (string) $data);
    }
}
