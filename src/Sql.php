<?php

namespace Adduc\SqlScript;

class Sql
{
    public function run($dir = false)
    {
        $config = new Configuration($dir ?: getcwd());
    }

}
