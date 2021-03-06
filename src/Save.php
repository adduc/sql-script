<?php

namespace Adduc\SqlScript;

class Save
{
    public function run($sql_dir, array $db_data, $stream = false)
    {
        $stream = is_resource($stream) ? $stream : false;

        $db_config = new DatabaseConfig();
        $db_config->validateDatabaseConfig($db_data);

        // Ensure $db_data['database'] is an array
        if (!is_array($db_data['database'])) {
            $db_data['database'] = array($db_data['database']);
        }

        foreach ($db_data['database'] as $database) {
            $command = $this->buildCommand($database, $sql_dir, $db_data);
            $msg = "Writing schema to {$sql_dir} directory.\n";
            $stream && fwrite($stream, $msg);
            exec($command);
        }
    }

    /**
     * @param string $sql_dir
     * @param array $db_config
     */
    public function buildCommand($database, $sql_dir, array $db_data)
    {
        $db_config = new DatabaseConfig();
        $db_config->validateDatabaseConfig($db_data);
        $db_data['port'] = isset($db_data['port']) ? $db_data['port'] : 3306;

        $mysqldump = exec("which mysqldump", $output, $return_var);
        if ($return_var !== 0) {
            $msg = "Could not find mysqldump in PATH.";
            throw new \Exception($msg);
        }

        // Build our mysqldump command.
        $command = 'MYSQL_PWD=%3$s %1$s -u%2$s -h%4$s -P%5$s %6$s --skip-dump-date -e -d -n';
        // Strip auto increment.
        $command .= " | sed 's/\\sAUTO_INCREMENT=[0-9]*\\b//'";
        // String engine.
        $command .= " | sed 's/\\sENGINE=[A-Za-z0-9_]*//'";
        // Strip definer.
        $command .= " | perl -p -e 's/\/\*(((?!\*\/).)*)DEFINER=(((?!\*\/).)*)\*\///g'";
        // Strip comments at beginning of line.
        $command .= " | sed 's/^--.*$//g'";
        // Output to schema file.
        $command .= " > {$sql_dir}/schema/%6\$s.schema.sql";

        $command = sprintf(
            $command,
            $mysqldump,
            $db_data['username'],
            $db_data['password'],
            $db_data['hostname'],
            $db_data['port'],
            $database
        );

        return $command;
    }
}
