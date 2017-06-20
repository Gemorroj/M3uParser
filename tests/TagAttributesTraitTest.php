<?php
namespace M3uParser\Tests;

use M3uParser\TagAttributesTrait;

class TagAttributesTraitTest extends \PHPUnit_Framework_TestCase
{
    use TagAttributesTrait;

    public function testAttributes()
    {
        $this->initAttributes('tvg-ID="" tvg-name="MEDI 1 SAT" group-title="ARABIC" tvg-name-custom=Первый_HD');

        $result = $this->getAttributes();

        self::assertInternalType('array', $result);
        self::assertEquals([
            'tvg-ID' => '',
            'tvg-name' => 'MEDI 1 SAT',
            'group-title' => 'ARABIC',
            'tvg-name-custom' => 'Первый_HD',
        ], $result);

        self::assertEquals('Первый_HD', $this->getAttribute('tvg-name-custom'));
        self::assertNull($this->getAttribute('fake-attribute'));
    }
}
