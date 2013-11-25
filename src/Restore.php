<?php

namespace Adduc\SqlScript;

class Restore
{
    public function run($sql_dir, array $config, $stream = false)
    {
        $stream = is_resource($stream) ? $stream : false;

        $dc_obj = new DatabaseConfig();
        $dc_obj->validateDatabaseConfig($config);

        $file = "{$sql_dir}/schema/{$config['database']}.schema.sql";
        $command = $this->buildCommand($file, $config);
        $stream && fwrite($stream, "Processing {$file}\n");
        exec($command);

        $dir = "{$sql_dir}/data";
        foreach (array_diff(scandir($dir), array('.', '..')) as $file) {
            $command = $this->buildCommand($file, $config);
            $stream && fwrite($stream, "Processing {$command}\n");
            exec($command);
        }
    }

    public function buildCommand($sql_file, $config)
    {
        $mysql = exec("which mysql", $output, $return_var);
        if ($return_var !== 0) {
            $msg = "Could not find mysql in PATH.";
            throw new \Exception($msg);
        }

        $command = sprintf(
            "%s -u%s -p%s -h%s %s < %s",
            $mysql,
            $config['username'],
            $config['password'],
            $config['hostname'],
            $config['database'],
            $sql_file
        );

        return $command;
    }
}
