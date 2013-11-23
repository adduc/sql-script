<?php

namespace Adduc\SqlScript;

class Sql
{
    public function run($dir = false, $output = false)
    {
        /**
         * * Load configuration.
         * * Load database configuration.
         * * Connect to Database.
         */


        $output = is_resource($output) ? $output : false;

        $loader = new ConfigurationLoader();
        $file = $loader->findConfigurationFile($dir ?: getcwd());
        $config = $loader->loadConfiguration($file);
        $output && fwrite($output, "Configuration loaded.\n");

        $db = new \PDO(
            $config->database['dsn'],
            $config->database['username'],
            $config->database['password']
        );
        $output && fwrite($output, "Connected to database.\n");

        foreach (array('sql_schema', 'sql_data') as $key) {

            $dir = dirname($file) . "/{$config->$key}";
            file_exists($dir) || $dir = $config->$key;
            $files = array_diff(scandir($dir), array('.', '..'));

            foreach ($files as $sql) {
                $output && fwrite($output, sprintf("Processing %s.\n", $sql));
                $db->exec("SOURCE {$sql};");
            }
        }
    }
}
