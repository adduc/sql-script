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

        $command = $this->buildDropCommand($config);
        $stream && fwrite($stream, "Deleting all tables in {$config['database']}\n");
        exec($command);

        $command = $this->buildCommand($config, $file);
        $stream && fwrite($stream, "Processing {$file}\n");
        exec($command);

        $dir = "{$sql_dir}/data";
        foreach (array_diff(scandir($dir), array('.', '..')) as $file) {
            $command = $this->buildCommand($file, $config);
            $stream && fwrite($stream, "Processing {$command}\n");
            exec($command);
        }
    }

    public function buildDropCommand($config)
    {
        $mysql = $this->findCommand('mysql');
        $mysqldump = $this->findCommand('mysqldump');

        $command = sprintf(
            '%1$s -u%3$s -p%4$s -h%5$s %6$s'
            . ' | grep ^DROP'
            . ' | %2$s -u%3$s -p%4$s -h%5$s %6$s',
            $mysqldump,
            $mysql,
            $config['username'],
            $config['password'],
            $config['hostname'],
            $config['database']
        );

        return $command;
    }

    public function buildCommand($config, $sql_file)
    {
        $mysql = $this->findCommand('mysql');

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

    public function findCommand($command)
    {
        $path = exec("which " . escapeshellarg($command), $output, $return_var);
        if ($return_var !== 0) {
            $msg = "Could not find {$command} in PATH.";
            throw new \Exception($msg);
        }
        return $path;
    }
}
