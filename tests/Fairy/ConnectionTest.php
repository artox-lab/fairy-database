<?php namespace Fairy;

use Fairy\Tests\DbTestCase;

class ConnectionTest extends DbTestCase
{
    protected $fixtures = [
        'core'
    ];

    public function testCore()
    {
        $this->assertEquals('mysql', $this->db()->getDriver());
        $this->assertEquals($this->pdo(), $this->db()->getPdo());
        $this->assertEquals($this->config, $this->db()->getConfig());
    }
}