<?php
namespace unit;
include __DIR__ . '/../../web.root/tq-peanut/application/config/peanut-bootstrap.php';

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
