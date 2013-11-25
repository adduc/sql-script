<?php

namespace Adduc\SqlScript;

class Save
{
    public function run($sql_dir, array $config, $stream = false)
    {
        $stream = is_resource($stream) ? $stream : false;

        $db_config = new DatabaseConfig();
        $db_config->validateDatabaseConfig($data);

        $command = $this->buildCommand($sql_dir, $data);
        exec($command);
    }

    /**
     * @param string $sql_dir
     * @param array $db_config
     */
    public function buildCommand($sql_dir, array $data)
    {
        $db_config = new DatabaseConfig();
        $db_config->validateDatabaseConfig($data);

        $mysqldump = exec("which mysqldump", $output, $return_var);
        if ($return_var !== 0) {
            $msg = "Could not find mysqldump in PATH.";
            throw new \Exception($msg);
        }

        // Build our mysqldump command.
        $command = "%s -u%s -p%s -h%s %s --skip-dump-date -e -d -n";
        // Strip auto increment.
        $command .= " | sed 's/\\sAUTO_INCREMENT=[0-9]*\\b//'";
        // Strip definer.
        $command .= " | perl -p -e 's/\/\*(((?!\*\/).)*)DEFINER=(((?!\*\/).)*)\*\///g'";
        // Strip comments at beginning of line.
        $command .= " | sed 's/^--.*$//g'";
        // Output to schema file.
        $command .= " > {$sql_dir}/schema/%5\$s.schema.sql";

        $command = sprintf(
            $command,
            $mysqldump,
            $data['username'],
            $data['password'],
            $data['hostname'],
            $data['database']
        );

        return $command;
    }
}
