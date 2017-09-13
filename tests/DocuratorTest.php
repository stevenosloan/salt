<?php

namespace Tests;

use Docurator;
use PHPUnit_Framework_TestCase as TestCase;

class DocuratorTest extends TestCase
{
    public function test_smokeTest()
    {
        $this->assertTrue(Docurator::smokeTest());
    }
}
