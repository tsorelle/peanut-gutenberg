<?php
namespace unit;
use Peanut\Bootstrap;
use PHPUnit\Framework\TestCase;


class BootstrapTest extends TestCase
{


    public function testGetSettings()
    {
        $actual = Bootstrap::getSettings();
        $this->assertNotEmpty($actual);
    }
}
