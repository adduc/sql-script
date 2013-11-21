<?php

namespace Adduc\SqlScript;

class Sql
{

    private $config_defaults = array(
        "database-json" => "config/database.json",
        "sql-schema" => "sql/schema",
        "sql-data" => "sql/data"
    );

    /**
     * Identify composer file with desired data, walking up directory tree
     * until found.
     *
     * @return array|false
     */
    public function getConfiguration($dir)
    {
        $dir = realpath($dir);

        while ($dir != dirname($dir)) {
            $file = "{$dir}/composer.json";

            switch (false) {
                case file_exists($file):
                case is_readable($file):
                case $file = file_get_contents($file):
                case $json = json_decode($file, true):
                case isset($json['extra']['adduc-sql']):
                case is_array($json['extra']['adduc-sql']):
                    break;
                default:
                    return $json['extra']['adduc-sql']
                        + array("dir" => $dir)
                        + $this->config_defaults;
            }
            $dir = dirname($dir);
        }
        return false;
    }

}
