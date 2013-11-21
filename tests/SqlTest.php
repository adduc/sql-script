<?php

namespace Adduc\SqlScript;
use org\bovigo\vfs\vfsStream;

class SqlTest extends \PHPUnit_Framework_TestCase
{

    protected $sql;

    public function setUp()
    {
        $this->sql = new Sql();
    }

    public function testRun()
    {
        $root = vfsStream::setup('root', null, array(
            'composer.json' => '{ "extra": { "adduc-sql": {} } }'
        ));

        $dir = vfsStream::url('root');
        $result = $this->sql->run($dir);
        $this->assertEmpty($result);
    }

}
