<?php

namespace Adduc\SqlScript;

class Restore
{
    public function run($sql_dir, array $config, $stream = false)
    {
        $stream = is_resource($stream) ? $stream : false;

        $dc_obj = new DatabaseConfig();
        $dc_obj->validateDatabaseConfig($config);

        // Ensure $config['database'] is an array
        if (!is_array($config['database'])) {
            $config['database'] = array($config['database']);
        }


        foreach ($config['database'] as $database) {
            $file = "{$sql_dir}/schema/{$config['database']}.schema.sql";

            $command = $this->buildDropCommand($database, $config);
            $stream && fwrite($stream, "Deleting all tables in {$config['database']}\n");
            exec($command);

            $command = $this->buildCommand($database, $config, $file);
            $stream && fwrite($stream, "Processing {$file}\n");
            exec($command);

            $dir = "{$sql_dir}/data";
            foreach (array_diff(scandir($dir), array('.', '..')) as $file) {
                if (stripos(basename($file), $database) !== 0) {
                    continue;
                }
                $command = $this->buildCommand($database, $config, $file);
                $stream && fwrite($stream, "Processing {$command}\n");
                exec($command);
            }
        }
    }

    public function buildDropCommand($database, $config)
    {
        $mysql = $this->findCommand('mysql');
        $mysqldump = $this->findCommand('mysqldump');

        $command = sprintf(
            'MYSQL_PWD=%4$s %1$s -u%3$s -h%5$s %6$s'
            . ' | grep ^DROP'
            . ' | %2$s -u%3$s -p%4$s -h%5$s %6$s',
            $mysqldump,
            $mysql,
            $config['username'],
            $config['password'],
            $config['hostname'],
            $database
        );

        return $command;
    }

    public function buildCommand($database, $config, $sql_file)
    {
        $mysql = $this->findCommand('mysql');

        $command = sprintf(
            'MYSQL_PWD=%3$s %1$s -u%2$s -h%4$s %5$s < %6$s',
            $mysql,
            $config['username'],
            $config['password'],
            $config['hostname'],
            $config['database'],
            $database
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
