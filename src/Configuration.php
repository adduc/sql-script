<?php

namespace Adduc\SqlScript;

/**
 * Very simple Configuration class.
 */
class Configuration
{

    public $database_file = "config/database.json";

    /**
     *
     */
    public $sql_schema = "sql/schema";

    /**
     *
     */
    public $sql_data = "sql/data";

    /**
     *
     */
    public $database = array(
        "hostname" => "localhost",
        "database" => "database",
        "username" => "username",
        "password" => "password"
    );

    public function __construct(array $data = array()) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

}
