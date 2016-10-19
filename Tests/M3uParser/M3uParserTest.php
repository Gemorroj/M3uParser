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

        //print_r($data);
        self::assertTrue(is_array($data));
        self::assertCount(5, $data);

        //var_dump($data);
    }

    public function testParseFile2()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/2.m3u');

        self::assertTrue(is_array($data));
        self::assertCount(9, $data);

        //var_dump($data);
    }


    public function testParseFile3()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/3.m3u');

        //print_r($data);
        self::assertTrue(is_array($data));
        self::assertCount(22, $data);

        //var_dump($data);
    }

    public function testParseFile4()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/4.m3u');

        //print_r($data);
        self::assertTrue(is_array($data));
        self::assertCount(7, $data);
    }

    public function testParseFile5()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/5.m3u');

        self::assertTrue(is_array($data));
        self::assertCount(234, $data);

        //var_dump($data);
    }

    public function testParseFile6()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/6.m3u');

        self::assertTrue(is_array($data));
        self::assertCount(47, $data);

        //print_r($data);
    }

    /**
     * @see https://github.com/Gemorroj/M3uParser/issues/4
     */
    public function testParseFile7()
    {
        $m3uParser = new M3uParser();
        $data = $m3uParser->parseFile($this->getFixturesDirectory() . '/7.m3u');

        self::assertTrue(is_array($data));
        self::assertCount(269, $data);

        //print_r($data);
    }
}
