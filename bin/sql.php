#!/usr/bin/env php
<?php

namespace Adduc\SqlScript;

if (php_sapi_name() != 'cli') {
    echo "This file can only be run via the command line.\n";
    exit(1);
}

include(__DIR__ . '/../vendor/autoload.php');

// Walk up directory until we find composer file.
$sql = new Sql();
$result = $sql->findComposerFile(getcwd());


if (!$result) {
    fwrite(STDERR, "Could not find a composer.json file with extra->adduc-sql set.\n");
    exit(1);
} else {
    /**
     * @var string $dir
     * @var array $json
     */
    explode($result);
}
