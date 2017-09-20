<?php

namespace Tests\Docurator;

use Docurator\Collection;
use Docurator\Configuration;
use PHPUnit_Framework_TestCase as TestCase;

class CollectionTest extends TestCase
{
    function setUp()
    {
        mkdir("tmp");
        mkdir("tmp/cat_a");
        mkdir("tmp/cat_a/b");
        mkdir("tmp/cat_b");
    }

    function tearDown()
    {
        $this->delTree("tmp");
    }

    function delTree($dir)
    {
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $file) {
            if (is_dir("{$dir}/{$file}")) {
                $this->delTree("{$dir}/{$file}");
            } else {
                unlink("{$dir}/{$file}");
            }
        }
        return rmdir($dir);
    }

    public function test_construct_throws_if_given_missing_directory()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Collection("hodor", Configuration::builtIn("less"));
    }

    public function test_generateFileTree_returns_array_of_path_to_matching_files()
    {
        $fixture_files = [
            "cat_a/one.ext",
            "cat_a/two.ext",
            "cat_a/b/three.ext",
            "root.ext",
            "cat_a/missing.not_match",
            "cat_b/some.not_match",
        ];

        foreach ($fixture_files as $fix) {
            touch("tmp/{$fix}");
        }

        $test_config = Configuration::builtIn("less", [
            "extensions" => ["ext"]
        ]);

        $coll      = new Collection("tmp", $test_config);
        $file_tree = $coll->generateFileTree();

        $this->assertEquals(
            [
                "cat_a",
                "cat_a/b",
                "root",
            ],
            array_keys($file_tree),
            "Categories should match, an empty dir should be root",
            /* delta */ 0.0,
            /* max depth */ 1,
            /* canonicalize */ true
        );

        /* we should only match files that ext matches */
        $this->assertEquals(2, count($file_tree["cat_a"]));
        $this->assertEquals(1, count($file_tree["cat_a/b"]));
        $this->assertEquals(1, count($file_tree["root"]));
    }
}
