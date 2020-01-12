<?php
namespace M3uParser\Tests\Tag;

use M3uParser\M3uEntry as M3uParserEntry;
use M3uParser\M3uParser;
use M3uParser\Tag\ExtLogo;
use M3uParser\Tag\ExtTagInterface;
use PHPUnit\Framework\TestCase;

class ExtLogoTest extends TestCase
{
    public function testParseFileExtTv(): void
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__ . '/../fixtures/extlogo.m3u');

        /** @var M3uParserEntry $entry */
        $entry = $data[0];

        self::assertEquals('Alternative\everclear_SMFTA.mp3', $entry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $entry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var ExtLogo $extLogo */
        $extLogo = $extTags[0];
        self::assertInstanceOf(ExtLogo::class, $extLogo);

        self::assertEquals('http://example.org/logo.png', $extLogo->getLogo());
    }
}
