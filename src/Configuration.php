<?php

namespace Adduc\SqlScript;

/**
 * Very simple Configuration class.
 */
class Configuration
{

    /** @var string File containing database configuration. */
    public $database_file = "config/database.json";

    /** @var string Directory containing SQL schema files to run. */
    public $sql_schema = "sql/schema";

    /** @var string Directory containing SQL data files to run. */
    public $sql_data = "sql/data";

    /** @var array Database configuration (loaded from database file.) */
    public $database = array(
        "dsn" => "mysql:dbname=database;host=127.0.0.1",
        "username" => "username",
        "password" => "password"
    );

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
