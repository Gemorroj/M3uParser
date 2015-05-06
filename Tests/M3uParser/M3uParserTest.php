<?php
namespace Tests\M3uParser;

use M3uParser\M3uParser;

class M3uParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseFileFail()
    {
        $this->setExpectedException('M3uParser\Exception');
        $m3uParser = new M3uParser();
        $m3uParser->parseFile('fake_file');
    }

    public function testParseFile1()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/../1.m3u');

        self::assertTrue(is_array($data));

        //var_dump($data);
    }

    public function testParseFile2()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/../2.m3u');

        self::assertTrue(is_array($data));

        //var_dump($data);
    }


    public function testParseFile3()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/../3.m3u');

        self::assertTrue(is_array($data));

        //var_dump($data);
    }

    public function testParseFile4()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/../4.m3u');

        self::assertTrue(is_array($data));

        //var_dump($data);
    }

    public function testParseFile5()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/../5.m3u');

        self::assertTrue(is_array($data));

        var_dump($data);
    }

    public function testParseFile6()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile(__DIR__ . '/../6.m3u');

        self::assertTrue(is_array($data));

        //var_dump($data);
    }
}
