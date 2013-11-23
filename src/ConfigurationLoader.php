<?php

namespace Adduc\SqlScript;

class ConfigurationLoader
{

    protected $loaded_files = array();

    /**
     * Traverse up directory tree until a composer.json file with
     * extra->adduc-sql is found.
     *
     * @throws \DomainException thrown if no files meeting critera could
     *         be found.
     * @param string $dir directory to begin in, and traverse up.
     * @return string fullpath to configuration file
     */
    public function findConfigurationFile($dir)
    {
        $dir = realpath($dir) ?: $dir;

        while ($dir != dirname($dir)) {
            $file = "{$dir}/composer.json";
            if ($this->validConfigurationFile($file)) {
                return $file;
            }
            $dir = dirname($dir);
        }

        $msg = "Could not find configuration file.";
        throw new \DomainException($msg);
    }

    /**
     * Load configuration
     */
    public function loadConfiguration($file)
    {
        if (!$this->validConfigurationFile($file)) {
            $msg = "'{$file}' does not point to a valid configuration file.";
            throw new \InvalidArgumentException($msg);
        }


        $data = $this->loaded_files[$file]['extra']['adduc-sql'];
        $config = new Configuration($data);

        $database_file = dirname($file) . "/{$config->database_file}";
        file_exists($database_file) || $database_file = $config->database_file;

        if (!$this->validJsonFile($database_file)) {
            throw new \DomainException("Could not load database file.");
        }

        $config->database = $this->loaded_files[$database_file];

        return $config;
    }

    /**
     * Determine whether first parameter points to a valid configuration file.
     *
     * @param string $file
     * @return boolean
     */
    protected function validConfigurationFile($file)
    {
        switch (true) {
            case !$this->validJsonFile($file):
            case !isset($this->loaded_files[$file]['extra']['adduc-sql']):
            case !is_array($this->loaded_files[$file]['extra']['adduc-sql']):
                return false;
            default:
                return true;
        }
    }

    /**
     * Determine whether first parameter points to a valid JSON file.
     *
     * @param string $file
     * @return boolean
     */
    protected function validJsonFile($file)
    {
        switch (false) {
            case file_exists($file):
            case is_readable($file):
            case $data = file_get_contents($file):
            case $json = json_decode($data, true):
                return false;
            default:
                $this->loaded_files[$file] = $json;
                return true;
        }
    }
}
