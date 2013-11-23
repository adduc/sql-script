<?php

namespace Adduc\SqlScript;
use org\bovigo\vfs\vfsStream;

class ConfigurationLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    public function setUp()
    {
        $this->loader = new ConfigurationLoader();
    }

    public function testFindConfigurationFile()
    {
        $root = vfsStream::setup('root', null, array(
            'composer.json' => '{ "extra": { "adduc-sql": {} } }'
        ));

        $dir = vfsStream::url('root');
        $file = $this->loader->findConfigurationFile($dir);
        $this->assertNotEmpty($file);
    }

    /**
     * @expectedException \DomainException
     */
    public function testFindConfigurationFile_expectedFailure()
    {
        $root = vfsStream::setup('root', null, array());
        $dir = vfsStream::url('root');
        $config = $this->loader->findConfigurationFile($dir);
    }

    public function testLoadConfigurationFile()
    {
        $root = vfsStream::setup('root', null, array(
            'composer.json' => '{"extra": {"adduc-sql": {"database_file": "database.json"}}}',
            'database.json' => '{"unique": "value"}'
        ));
        $file = vfsStream::url('root/composer.json');
        $config = $this->loader->loadConfiguration($file);
        $this->assertTrue($config instanceof Configuration);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadConfiguration_invalidFile()
    {
        $root = vfsStream::setup('root', null, array());
        $file = vfsStream::url('root/composer.json');
        $config = $this->loader->loadConfiguration($file);
    }

    /**
     * @expectedException \DomainException
     */
    public function testLoadConfiguration_noDatabase()
    {
        $root = vfsStream::setup('root', null, array(
            'composer.json' => '{ "extra": { "adduc-sql": {} } }'
        ));
        $file = vfsStream::url('root/composer.json');
        $config = $this->loader->loadConfiguration($file);
    }

}
