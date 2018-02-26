<?php
namespace M3uParser\Tests;

use M3uParser\Exception;
use M3uParser\Tag\ExtInf;
use M3uParser\TagsManagerTrait;

class TagsManagerTraitTest extends \PHPUnit_Framework_TestCase
{
    use TagsManagerTrait;

    public function testTags()
    {
        $this->addTag(ExtInf::class);

        $result = $this->getTags();

        self::assertInternalType('array', $result);
        self::assertEquals([ExtInf::class], $result);
    }


    public function testClearTags()
    {
        $this->addTag(ExtInf::class);
        $this->clearTags();

        $result = $this->getTags();

        self::assertInternalType('array', $result);
        self::assertEquals([], $result);
    }


    public function testErrorTags()
    {
        $this->expectException(Exception::class);
        $this->addTag(self::class);
    }
}
