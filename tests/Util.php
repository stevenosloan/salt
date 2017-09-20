<?php

namespace Tests;

class Util
{
    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $file) {
            if (is_dir("{$dir}/{$file}")) {
                self::delTree("{$dir}/{$file}");
            } else {
                unlink("{$dir}/{$file}");
            }
        }
        return rmdir($dir);
    }
}
