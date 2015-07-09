<?php namespace Fairy;

use Fairy\Query\ResultsProcessor;
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

    public function testCollectedSelect()
    {
        $expectedSQL = 'SELECT '
            . '`address_id` AS `address___id`, '
            . '`address`.`address` AS `address___address`, '
            . '`address`.`city_id` AS `address___city_id`, '
            . '`city`.`id` AS `city___id`, '
            . '`country`.`country` AS `country___country`, '
            . '`country`.`id` AS `country___id`, '
            . '`store_id` AS `store___id`, '
            . '`city`.`city` AS `city___city` '
            . 'FROM `address`';

        $query = $this->db()
            ->query()
            ->select([
                'address' => [
                    'address_id' => 'id',
                    'address',
                    'city_id',

                    WITH_ONE => [
                        'city' => [
                            'id',

                            WITH_ONE => [
                                'country' => [
                                    'country'
                                ]
                            ]
                        ]
                    ],

                    WITH_MANY => [
                        'store' => [
                            'store_id' => 'id'
                        ],

                        'city' => [
                            'city',
                        ]
                    ]
                ]
            ])
            ->from('address');

        $this->assertEquals($expectedSQL, $query->getQuery()->getRawSql());
    }

    public function testProcessResultColumnsCollector()
    {

    }
}