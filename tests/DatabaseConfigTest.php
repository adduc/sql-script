<?php

namespace Adduc\SqlScript;

class DatabaseConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $db_config;

    public function setUp()
    {
        $this->db_config = new DatabaseConfig();
    }

    public function testValidateDatabaseConfig()
    {
        $data = array("hostname" => 0, "database" => 0, "username" => 0, "password" => 0);
        $result = $this->db_config->validateDatabaseConfig($data);
        $this->assertTrue($result);
    }

    /**
     * @expectedException Exception
     * @dataProvider provideValidateDatabaseConfigMissingData
     */
    public function testValidateDatabaseConfigMissingData(array $db_config)
    {
        $this->db_config->validateDatabaseConfig();
    }

    public function provideValidateDatabaseConfigMissingData()
    {
        return array(
            array(null),
            array(array("hostname" => 0, "database" => 0, "username" => 0)),
            array(array("hostname" => 0, "database" => 0, "password" => 0)),
            array(array("hostname" => 0, "username" => 0, "password" => 0)),
            array(array("database" => 0, "username" => 0, "password" => 0))
        );
    }
}
