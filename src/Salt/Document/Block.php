<?php

namespace Salt\Document;

use Symfony\Component\Yaml\Yaml;

class Block
{
    public $file_info;
    public $open_line_number;
    public $end_line_number;
    public $data;

    private $buffer = [];

    public function __construct(\SplFileInfo $file_info)
    {
        $this->file_info = $file_info;
    }

    public function bufferLine($line)
    {
        $this->buffer[] = $line;
        return $this;
    }

    public function open($line_number)
    {
        $this->open_line_number = $line_number;
        return $this;
    }

    public function isOpened()
    {
        return !is_null($this->open_line_number);
    }

    public function close()
    {
        return $this;
    }

    public function finalize($line_number)
    {
        if (is_null($this->open_line_number)) {
            throw new \Exception("Shouldn't finalize until block has been opened");
        }

        $this->end_line_number = $line_number;
        $this->data = Yaml::parse(implode("\n", $this->buffer));

        return $this;
    }
}
