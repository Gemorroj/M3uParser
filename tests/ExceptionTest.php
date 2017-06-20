<?php
namespace M3uParser\Tests;

use M3uParser\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        self::assertInstanceOf('Exception', new Exception);
    }
}
