#!/usr/bin/env php
<?php

/**
 * This script takes in command line arguments, and uses them to
 * write a database.json file at the base of the repository.
 *
 * It is provided for use by the build server for this repository, and may
 * change at any time.
 *
 * Example Usage: php createDatabaseJson.php hostname=h\&username=u\&password=p\&database=d
 */

namespace Adduc\SqlScript;

if (php_sapi_name() != 'cli') {
    die("This script can only be run via command line.");
}

require(__DIR__ . '/../../vendor/autoload.php');


try {
    // Read in arguments.
    parse_str(end($argv), $data);

    // Validate Data.
    $db_config = new DatabaseConfig();
    $db_config->validateDatabaseConfig($data);

    $file = __DIR__ . '/../../database.json';
    if(file_exists($file)) {
        throw new \Exception("File already exists. Will not overwrite.\n");
    }

    fwrite(STDOUT, "Writing to {$file}");
    file_put_contents($file, json_encode($data));

} catch (\Exception $e) {
    fwrite(STDERR, "{$e->getMessage()}\n");
    exit(1);
}

