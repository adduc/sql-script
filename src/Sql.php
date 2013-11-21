<?php

namespace Adduc\SqlScript;

class Sql
{

    /**
     * Identify composer file with desired data, walking up directory tree
     * until found.
     *
     * @return array|false
     */
    public function findComposerFile($dir)
    {
        $dir = realpath($dir);
        while ($dir != dirname($dir)) {
            $file = "{$dir}/composer.json";
            switch (true) {
                case !file_exists($file):
                case !is_readable($file):
                case !($json = json_encode($file, true)):
                case !isset($json['extra']['adduc-sql']):
                    break;
                default:
                    return array("dir" => $dir, "json" => $json);
            }
            $dir = dirname($dir);
        }
        return false;
    }

}
