<?php

namespace Adduc\SqlScript;

class SqlTest extends \PHPUnit_Framework_TestCase
{

    public function testLocation()
    {
        $sql = new Sql();
        $sql->findComposerFile('.');
    }

}
