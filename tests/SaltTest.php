<?php

namespace Tests;

use Salt;
use PHPUnit_Framework_TestCase as TestCase;

class SaltTest extends TestCase
{
    public function test_smokeTest()
    {
        $this->assertTrue(Salt::smokeTest());
    }
}
