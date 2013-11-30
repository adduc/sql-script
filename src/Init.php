<?php

namespace Adduc\SqlScript;

class Init
{
    public function run($dir, array $db_data, $stream = false)
    {
        $stream = is_resource($stream) ? $stream : false;

        $dirs = array(
            'sql' => "{$dir}/sql",
            'data' => "{$dir}/sql/data",
            'schema' => "{$dir}/sql/schema"
        );

        foreach ($dirs as $name => $dir) {
            if (!is_dir($dir)) {
                $msg = "Creating {$name} directory at {$dir}\n";
                $stream && fwrite($stream, $msg);
                mkdir($dir);
            }
        }

        $database_file = "{$dirs['sql']}/database.json";

        if (!is_file($database_file)) {
            $msg = "Writing sample database config to {$database_file}\n";
            $stream && fwrite($stream, $msg);
            $data = array(
                'hostname' => '',
                'database' => '',
                'username' => '',
                'password' => ''
            );

            file_put_contents($database_file, json_encode($data));
        }
    }
}
