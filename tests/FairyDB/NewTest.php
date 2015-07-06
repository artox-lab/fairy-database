<?php namespace Fairy;

use Fairy\Tests\DbTestCase;

class NewTest extends DbTestCase
{
    protected $fixtures = [
        'core'
    ];

    public function testNew()
    {
        $this->getConnection();
        $this->getDataSet();
        $this->assertTrue(true);
    }

    public function testNew2()
    {
        $this->assertTrue(true);
    }
}