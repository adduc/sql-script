#!/usr/bin/env php
<?php

namespace Adduc\SqlScript;

/**
 * Check that we're running via command line.
 */
if (php_sapi_name() != 'cli') {
    echo "This file can only be run via the command line.\n";
    exit(1);
}

/**
 * Include composer for handling autoloading.
 */
include(__DIR__ . '/../vendor/autoload.php');

try {
    $sql = new Sql();
    $sql->run(getcwd(), STDOUT);
} catch (\Exception $e) {
    fwrite(STDERR, "{$e->getMessage()}\n");
    exit(1);
}
