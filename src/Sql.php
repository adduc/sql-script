<?php

namespace Adduc\SqlScript;

class Sql
{

    public function run($dir, $command, $resource)
    {
        $resource = is_resource($resource) ? $resource : false;
        $dc_obj = new DatabaseConfig();

        switch (false) {
            case $dir = $this->identifySqlDir($dir):
                $msg = "Could not find SQL directory.";
                throw new \Exception($msg);

            case $file = file_get_contents("{$dir}/database.json"):
                $msg = "Could not read database configuration.";
                throw new \Exception($msg);

            case $db_config = json_decode($file, true):
                $msg = "Could not decode db_config.";
                throw new \Exception($msg);

            case $dc_obj->validateDatabaseConfig($db_config):
                $msg = "Invalid database configuration.";
                throw new \Exception($msg);

            case in_array($command, array('restore', 'save')):
                $msg = "Unrecognized Command.";
                throw new \InvalidArgumentException($msg);
        }

        $class = __NAMESPACE__ . "\\" . ucfirst($command);
        $obj = new $class();
        $obj->run($dir, $db_config, $resource);
    }

    public function identifySqlDir($dir)
    {
        switch (true) {
            case !is_dir("{$dir}/sql"):
            case !is_dir("{$dir}/sql/data"):
            case !is_dir("{$dir}/sql/schema"):
            case !is_file("{$dir}/sql/database.json"):
                return dirname($dir) != $dir
                    ? $this->identifySqlDir(dirname($dir)) : false;
            default:
                return "{$dir}/sql";
        }
    }
}
