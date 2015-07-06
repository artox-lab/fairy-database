<?php namespace Fairy;

use Fairy\Tests\DbTestCase;

class NewTest extends DbTestCase
{
    protected $fixtures = [
        'core'
    ];

    public function testNew()
    {
        $this->assertEquals('mysql', $this->db()->getAdapter());
        $this->assertEquals($this->pdo(), $this->db()->getPdo());
    }

    public function testNew2()
    {
        $this->assertTrue(true);
    }
}