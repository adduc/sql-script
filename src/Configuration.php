<?php

namespace Adduc\SqlScript;

class Configuration
{
    public $data = array(
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
    public function __construct($dir)
    {
        $dir = realpath($dir) ?: $dir;

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
                    $this->data['dir'] = $dir;
                    $this->data = $json['extra']['adduc-sql'] + $this->data;
                    return;
            }
            $dir = dirname($dir);
        }
        $msg = "Could not find composer configuration file with extra.adduc-sql";
        throw new \DomainException($msg);
    }
}
