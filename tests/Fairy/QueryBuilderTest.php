<?php namespace Fairy;

use Fairy\Tests\DbTestCase;

class QueryBuilderTest extends DbTestCase
{
    protected $fixtures = [
        'core'
    ];

    public function testSimpleSelect()
    {
        $expectedSQL = 'SELECT `address_id`, `address`, `city_id` FROM `address`';

        $query = $this->db()
            ->query()
            ->select([
                'address_id',
                'address',
                'city_id'
            ])
            ->from('address');

        $this->assertEquals($expectedSQL, $query->getQuery()->getRawSql());

        $query = $this->db()
            ->query()
            ->select('address_id', 'address', 'city_id')
            ->from('address');

        $this->assertEquals($expectedSQL, $query->getQuery()->getRawSql());
    }
}