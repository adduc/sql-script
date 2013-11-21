#!/usr/bin/env php
<?php

namespace Adduc\SqlScript;

if (php_sapi_name() != 'cli') {
    echo "This file can only be run via the command line.\n";
    exit(1);
}

include(__DIR__ . '/../vendor/autoload.php');

// Find our configuration.
$sql = new Sql();
$config = $sql->getConfiguration(getcwd());

if (!$config) {
    fwrite(STDERR, "Could not find a composer.json file with extra->adduc-sql set.\n");
    exit(1);
}

var_dump($config);
