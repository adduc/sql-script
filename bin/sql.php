#!/usr/bin/env php
<?php

namespace Adduc\SqlScript;

if (php_sapi_name() != "cli") {
    die("Must be run in command line mode.");
}

foreach (array('', '/../../../') as $prefix) {
    $file = __DIR__ . "{$prefix}/../vendor/autoload.php";
    if (file_exists($file)) {
        include($file);
    }
}

try {
    $sql = new Sql();
    $sql->run(getcwd(), end($argv), STDOUT);
} catch (\InvalidArgumentException $e) {
    fwrite(STDERR, "Usage: <script> [restore|save]\n");
    exit(1);
} catch (\Exception $e) {
    fwrite(STDERR, "{$e->getMessage()}\n");
    exit(1);
}
