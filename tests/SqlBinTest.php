<?php

namespace Adduc\SqlScript;

class SqlBinTest extends \PHPUnit_Framework_TestCase
{
    public function testIsExecutable()
    {
        $file = __DIR__ . '/../bin/sql.php';
        $this->assertTrue(is_executable($file));
    }
}
