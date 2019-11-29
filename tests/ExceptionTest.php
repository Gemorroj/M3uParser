<?php
namespace M3uParser\Tests;

use M3uParser\Exception;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function test(): void
    {
        self::assertInstanceOf('Exception', new Exception);
    }
}
