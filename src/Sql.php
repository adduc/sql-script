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
    }

}
