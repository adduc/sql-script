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
        $result = $this->sql->getConfiguration(__DIR__);
        $this->assertNotEmpty($result);
    }

    public function testGetConfiguration_expectedFailure()
    {
        $dir = vfsStream::url('exampleDir');
        $result = $this->sql->getConfiguration($dir);
        $this->assertEmpty($result);
    }

}
