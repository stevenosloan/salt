<?php

namespace Tests\Salt;

use Salt\Configuration;
use Salt\Document;

use Tests\Util;
use PHPUnit_Framework_TestCase as TestCase;

class DocumentTest extends TestCase
{
    public function setUp()
    {
        mkdir("tmp");
    }

    public function tearDown()
    {
        Util::delTree("tmp");
    }

    public function test_scan()
    {
        file_put_contents("tmp/test.less", <<<EOF
/** doc
hello: world
*/
.some_css {
    display: none;
}

/** doc
test:
 - what
 - a
 - wonderful
 - world
*/
EOF
        );
        $config  = Configuration::builtIn("less");
        $file    = new \SplFileInfo("tmp/test.less");

        $subject = new Document($config, $file);

        $this->assertSame($config, $subject->config);
        $this->assertSame($file, $subject->file_info);

        $this->assertCount(2, $subject->blocks);
        $this->assertContainsOnlyInstancesOf(Document\Block::class, $subject->blocks);

        $first_block = $subject->blocks[0];
        $second_block = $subject->blocks[1];

        $this->assertSame(
            ["hello" => "world"],
            $first_block->data
        );
        $this->assertSame(0, $first_block->open_line_number);
        $this->assertSame(2, $first_block->end_line_number);

        $this->assertSame(
            ["test" => ["what", "a", "wonderful", "world"]],
            $second_block->data
        );
        $this->assertSame(7, $second_block->open_line_number);
        $this->assertSame(13, $second_block->end_line_number);
    }
}
