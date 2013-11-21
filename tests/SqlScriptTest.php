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

    public function testGetConfiguration()
    {
        $root = vfsStream::setup('root', null, array(
            'composer.json' => '{ "extra": { "adduc-sql": {} } }'
        ));

        $dir = vfsStream::url('root');
        $result = $this->sql->getConfiguration($dir);
        $this->assertNotEmpty($result);
    }

    public function testGetConfiguration_expectedFailure()
    {
        $root = vfsStream::setup('root', null, array());
        $dir = vfsStream::url('root');
        $result = $this->sql->getConfiguration($dir);
        $this->assertEmpty($result);
    }

}
