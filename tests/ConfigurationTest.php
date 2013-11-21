<?php

namespace Adduc\SqlScript;
use org\bovigo\vfs\vfsStream;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testGetConfiguration()
    {
        $root = vfsStream::setup('root', null, array(
            'composer.json' => '{ "extra": { "adduc-sql": {} } }'
        ));

        $dir = vfsStream::url('root');
        $config = new Configuration($dir);
        $this->assertNotEmpty($config->data['dir']);
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetConfiguration_expectedFailure()
    {
        $root = vfsStream::setup('root', null, array());
        $dir = vfsStream::url('root');
        $config = new Configuration($dir);
    }

}
