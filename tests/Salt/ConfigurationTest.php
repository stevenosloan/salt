<?php

namespace Tests\Salt;

use Salt\Configuration;
use PHPUnit_Framework_TestCase as TestCase;

class ConfigurationTest extends TestCase
{
    function test_builtIn_uses_builtin_defaults_with_overrides_available()
    {
        $subject_a = Configuration::builtIn("less");
        $this->assertArraySubset($subject_a->options, Configuration::BUILT_INS["less"]);

        $subject_b = Configuration::builtIn("sass", [
            "block_open" => "hodor",
            "block_close" => "hodor!",
        ]);
        $this->assertArraySubset(
            $subject_b->options,
            [
                "extensions" => Configuration::BUILT_INS["sass"]["extensions"],
                "block_open" => "hodor",
                "block_close" => "hodor!",
            ]
        );
    }

    function test_construct_throws_if_given_invalid_schema()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Configuration(["extensions" => "should have been an array"]);
    }
}
