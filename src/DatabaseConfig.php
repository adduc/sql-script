<?php

namespace Adduc\SqlScript;

class DatabaseConfig
{
    public function validateDatabaseConfig($db_config)
    {
        switch(true) {
            case !is_array($db_config):
                throw new \Exception("Configuration is not valid.");
            case !isset($db_config['hostname']):
                throw new \Exception("Hostname is not valid.");
            case !isset($db_config['database']):
                throw new \Exception("Database is not valid.");
            case !isset($db_config['username']):
                throw new \Exception("Username is not valid.");
            case !isset($db_config['password']):
                throw new \Exception("Password is not valid.");
            default:
                return true;
        }
    }
}
