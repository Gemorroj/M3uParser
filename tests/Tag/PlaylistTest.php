<?php

namespace M3uParser\Tests\Tag;

use M3uParser\M3uData;
use M3uParser\M3uEntry;
use M3uParser\M3uParser;
use M3uParser\Tag\ExtTagInterface;
use M3uParser\Tag\Playlist;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class PlaylistTest extends TestCase
{
    public function testParsePlaylist(): void
    {
        $m3uParser = new M3uParser();
        $m3uParser->addDefaultTags();
        $data = $m3uParser->parseFile(__DIR__.'/../fixtures/playlist.m3u');

        /** @var M3uEntry $entry */
        $entry = $data[0];

        self::assertEquals('rtp://@127.0.0.1:5003', $entry->getPath());

        /** @var ExtTagInterface[] $extTags */
        $extTags = $entry->getExtTags();
        self::assertCount(1, $extTags);

        /** @var Playlist $playlist */
        $playlist = $extTags[0];
        self::assertInstanceOf(Playlist::class, $playlist);

        self::assertEquals('My favorite playlist', $playlist->getValue());
    }

    public function testGeneratePlaylist(): void
    {
        $expectedString = '#EXTM3U'."\n";
        $expectedString .= '#PLAYLIST:My favorite playlist'."\n";
        $expectedString .= 'test-path';

        $entry = new M3uEntry();
        $entry->setPath('test-path');
        $entry->addExtTag(
            (new Playlist())
                ->setValue('My favorite playlist')
        );

        $data = new M3uData();
        $data->append($entry);

        self::assertEquals($expectedString, (string) $data);
    }
}
