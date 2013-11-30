<?php

namespace Adduc\SqlScript;

use org\bovigo\vfs\vfsStream;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    protected $save;

    public function setUp()
    {
        $this->save = new Save();
    }

    public function testRun()
    {
        $this->markTestIncomplete();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Database Config:
     */
    public function testRunInvalidDatabase()
    {
        vfsStream::setup('root');
        $dir = vfsStream::url('root');
        $db_config = array();
        $this->save->run($dir, array());
    }
}
