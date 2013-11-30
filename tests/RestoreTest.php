<?php

namespace Adduc\SqlScript;

class RestoreTest extends \PHPUnit_Framework_TestCase
{
    protected $restore;

    public function setUp()
    {
        $this->restore = new Restore();
    }

    public function testRun()
    {
        $this->markTestIncomplete();
    }

    public function testBuildDropCommand()
    {
        $this->markTestIncomplete();
    }

    public function testBuildCommand()
    {
        $this->markTestIncomplete();
    }

    public function testFindCommand()
    {
        $which = trim(exec('which which'));
        $output = $this->restore->findCommand('which');
        $this->assertEquals($which, $output);
    }

    /**
     *  @expectedException Exception
     */
    public function testFindCommandFail()
    {
        $command = "adduc!@#$%^&*";
        $output = $this->restore->findCommand($command);
        echo $output;
    }
}
