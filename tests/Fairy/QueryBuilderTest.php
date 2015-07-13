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
        $processor = new ResultsProcessor();

        $columns = $processor->columnsCollector([
            'staff' => [
                'staff.staff_id' => 'id',
                'first_name',
                'last_name',
                'username'
            ]
        ]);

        $this->assertEquals([
            'staff.staff_id AS staff___id',
            'staff.first_name AS staff___first_name',
            'staff.last_name AS staff___last_name',
            'staff.username AS staff___username',
        ], $columns);

        $columns = $processor->columnsCollector([
            'staff' => [
                'staff.staff_id' => 'id',
                'first_name',
                'last_name',
                'username',

                WITH_ONE => [
                    'address' => [
                        'address.address_id' => 'id',

                        WITH_ONE => [
                            'city' => [
                                'city.city_id' => 'id',
                                'city',

                                WITH_ONE => [
                                    'country' => [
                                        'country.country_id' => 'id',
                                        'country'
                                    ]
                                ]
                            ]
                        ]
                    ],

                    'store' => [
                        'store.store_id' => 'id',

                        WITH_ONE => [
                            'storeAddress' => [
                                'storeAddress.address_id' => 'id',
                                'address',
                                'district',
                                'phone'
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals([
            'staff.staff_id AS staff___id',
            'staff.first_name AS staff___first_name',
            'staff.last_name AS staff___last_name',
            'staff.username AS staff___username',

            'address.address_id AS address___id',
            'city.city_id AS city___id',
            'city.city AS city___city',
            'country.country_id AS country___id',
            'country.country AS country___country',

            'store.store_id AS store___id',
            'storeAddress.address_id AS storeAddress___id',
            'storeAddress.address AS storeAddress___address',
            'storeAddress.district AS storeAddress___district',
            'storeAddress.phone AS storeAddress___phone',
        ], $columns);
    }
}