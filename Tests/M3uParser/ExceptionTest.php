<?php
namespace Tests\M3uParser;

use M3uParser\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->assertInstanceOf('Exception', new Exception);
    }
}
