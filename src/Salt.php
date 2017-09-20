<?php

class Salt
{
    public static function smokeTest()
    {
        return true;
    }

    public static function collection($src, $type, $overrides = [])
    {
        if (array_key_exists($type, Salt\Configuration::BUILT_INS)) {
            $config = Salt\Configuration::builtIn($type, $overrides);
        } else {
            $config = new Salt\Configuration($overrides);
        }

        return new Salt\Collection($src, $config);
    }
}
