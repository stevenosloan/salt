<?php

namespace Docurator;

class Document
{
    public $config;
    public $file_info;
    public $blocks = [];

    public function __construct(Configuration $config, \SplFileInfo $file_info)
    {
        $this->config        = $config;
        $this->file_info     = $file_info;
        $this->scan();
    }

    public function scan()
    {
        $current_block = new Document\Block($this->file_info);
        foreach ($this->file_info->openFile() as $idx => $line) {
            if ($current_block->isOpened()) {
                // scan for close line
                if (preg_match("/{$this->config->getBlockClose()}/i", $line)) {
                    // finalize the block
                    $current_block->finalize($idx);
                    // push into the blocks array
                    $this->blocks[] = $current_block;
                    // create a new current block
                    $current_block = new Document\Block($this->file_info);
                } else {
                    $current_block->bufferLine($line);
                }
            } else {
                if (preg_match("/{$this->config->getBlockOpen()}/i", $line)) {
                    $current_block->open($idx);
                }
            }
        }
    }
}
