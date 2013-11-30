<?php

namespace Adduc\SqlScript;

class DatabaseConfig
{
    public function validateDatabaseConfig($db_config)
    {
        switch(true) {
            case !is_array($db_config):
                $msg = "Database Config: Configuration is not valid.";
                throw new \Exception($msg);
            case !isset($db_config['hostname']):
                $msg = "Database Config: Hostname is not valid.";
                throw new \Exception($msg);
            case !isset($db_config['database']):
                $msg = "Database Config: Database is not valid.";
                throw new \Exception($msg);
            case !isset($db_config['username']):
                $msg = "Database Config: Username is not valid.";
                throw new \Exception($msg);
            case !isset($db_config['password']):
                $msg = "Database Config: Password is not valid.";
                throw new \Exception($msg);
            default:
                return true;
        }
    }
}
