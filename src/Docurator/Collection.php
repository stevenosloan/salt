<?php

namespace Docurator;

class Collection
{

    /**
     * @var string
     */
    public $source_directory;

    /**
     * @var array
     */
    public $file_tree;

    /**
     * @var Configuration
     */
    public $config;

    public function __construct($source_directory, Configuration $config)
    {
        if (filetype(realpath($source_directory)) !== "dir") {
            throw new \InvalidArgumentException("the given source_directory must be a directory");
        }

        $this->source_directory = realpath($source_directory);
        $this->config           = $config;
        $this->file_tree        = $this->generateFileTree();
    }

    /**
     * @return array
     */
    public function generateFileTree()
    {
        $file_tree        = [];
        $source_dir_regex = preg_quote($this->source_directory, "/");

        foreach ($this->matchingFiles() as $file_info) {
            $relative_path = preg_replace("/^{$source_dir_regex}\/?/i", "", $file_info->getPath());

            if (empty($relative_path)) {
                $relative_path = "root";
            }

            if (!array_key_exists($relative_path, $file_tree)) {
                $file_tree[$relative_path] = [];
            }

            $file_tree[$relative_path][] = $file_info;
        }

        return $file_tree;
    }

    private function matchingFiles()
    {
        $directory_iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->source_directory)
        );
        $extensions = implode("|", $this->config->getExtensions());
        $regex_iterator = new \RegexIterator(
            $directory_iterator,
            "/\.({$extensions})$/i",
            \RecursiveRegexIterator::MATCH
        );

        $files = [];
        foreach ($regex_iterator as $file_info) {
            $files[] = $file_info;
        }

        return $files;
    }
}
