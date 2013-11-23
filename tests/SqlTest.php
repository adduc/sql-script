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
            'composer.json' => '{"extra": { "adduc-sql": {
                "database_file": "database.json",
                "sql_schema": "sql_schema",
                "sql_data": "sql_data"
            }}}',
            'database.json' => '{"dsn": "sqlite::memory:"}',
            "sql_schema" => array(
                "schema.test.sql" => "SQL ERROR"
            ),
            "sql_data" => array(
                "data.test.sql" => ""
            )
        ));

        $dir = vfsStream::url('root');
        $stream = fopen("php://memory", 'r+');
        $result = $this->sql->run($dir, $stream);

        $output = $this->readStream($stream);
        $this->assertContains('schema.test.sql', $output);
        $this->assertContains('data.test.sql', $output);
    }

    /**
     * Given a stream, rewind and read all its contents.
     *
     * @param resource $stream
     * @return string
     */
    protected function readStream($stream)
    {
        rewind($stream);
        $buffer = "";
        while (!feof($stream)) {
            $buffer .= fgets($stream);
        }
        return $buffer;
    }
}
