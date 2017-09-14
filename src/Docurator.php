<?php

class Docurator
{
    public static function smokeTest()
    {
        return true;
    }

    public static function collection($src, $type, $overrides = [])
    {
        if (array_key_exists($type, Docurator\Configuration::BUILT_INS)) {
            $config = Docurator\Configuration::builtIn($type, $overrides);
        } else {
            $config = new Docurator\Configuration($overrides);
        }

        return new Docurator\Collection($src, $config);
    }
}
