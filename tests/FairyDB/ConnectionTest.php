<?php namespace FairyDB;

use Mockery as m;
class ConnectionTest extends TestCase
{
    private $mysqlConnectionMock;
    private $connection;

    public function setUp()
    {
        parent::setUp();

        $this->mysqlConnectionMock = m::mock('\\FairyDB\\ConnectionAdapters\\Mysql');
        $this->mysqlConnectionMock->shouldReceive('connect')->andReturn($this->mockPdo);

        $this->container->setInstance('\\FairyDB\\ConnectionAdapters\\Mysqlmock', $this->mysqlConnectionMock);
        $this->connection = new Connection('mysqlmock', array('prefix' => 'cb_'), null, $this->container);
    }

    public function testConnection()
    {
        $this->assertEquals($this->mockPdo, $this->connection->getPdoInstance());
        $this->assertInstanceOf('\\PDO', $this->connection->getPdoInstance());
        $this->assertEquals('mysqlmock', $this->connection->getAdapter());
        $this->assertEquals(array('prefix' => 'cb_'), $this->connection->getAdapterConfig());
    }

    public function testQueryBuilderAliasCreatedByConnection()
    {
        $mockQBAdapter = m::mock('\\FairyDB\\QueryBuilder\\Adapters\\Mysql');

        $this->container->setInstance('\\FairyDB\\QueryBuilder\\Adapters\\Mysqlmock', $mockQBAdapter);
        $connection = new Connection('mysqlmock', array('prefix' => 'cb_'), 'DBAlias', $this->container);
        $this->assertEquals($this->mockPdo, $connection->getPdoInstance());
        $this->assertInstanceOf('\\FairyDB\\QueryBuilder\\QueryBuilderHandler', \DBAlias::newQuery());
    }
}