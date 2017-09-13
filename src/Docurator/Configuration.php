<?php

namespace Docurator;

class Configuration
{
    const BUILT_INS = [
        "less" => [
            "extensions"  => ["less", "css"],
            "block_open"  => "/** doc",
            "block_close" => "*/",
        ],
        "sass" => [
            "extensions"  => ["sass", "scss", "css"],
            "block_open"  => "/** doc",
            "block_close" => "*/",
        ],
    ];

    const SCHEMA = [
        "extensions"  => "array",
        "block_open"  => "string",
        "block_close" => "string",
    ];

    /**
     * @var array
     */
    public $options;

    /**
     * @param string $type
     * @param array  $overrides configuration to overwite the default from BUILT_INS
     *
     * @return Configuration
     */
    public static function builtIn($type, array $overrides = [])
    {
        return new Configuration(array_merge(static::BUILT_INS[$type], $overrides));
    }

    /**
     * @param array $options options for configuration, should match static::SCHEMA types
     *
     * @return Configuration
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options)
    {
        foreach (static::SCHEMA as $key => $type) {
            $given_type = gettype($options[$key]);
            if ($given_type !== $type) {
                throw new \InvalidArgumentException("given configuration had an invalid type for '{$key}'. Given {$given_type} expected {$type}");
            }
        }

        $this->options = $options;
    }

    /**
     * @return string[]
     */
    public function getExtensions()
    {
        return $this->options["extensions"];
    }

    /**
     * @return string
     */
    public function getBlockOpen()
    {
        return $this->options["block_open"];
    }


    /**
     * @return string
     */
    public function getBlockClose()
    {
        return $this->options["block_close"];
    }
}
