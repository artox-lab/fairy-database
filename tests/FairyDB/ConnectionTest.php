<?php namespace Fairy;

class ConnectionTest extends TestCase
{
    private $mysqlConnectionMock;
    private $connection;
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = new Container();

        $this->mysqlConnectionMock = m::mock('\\Fairy\\ConnectionAdapters\\Mysql');
        $this->mysqlConnectionMock->shouldReceive('connect')->andReturn($this->mockPdo);

        $this->container->setInstance('\\Fairy\\ConnectionAdapters\\Mysqlmock', $this->mysqlConnectionMock);
        $this->connection = new Connection('mysqlMock', array('prefix' => 'cb_'));
        $this->connection->setPdoInstance($this->mockPdo);
    }

    public function testConnection()
    {
        $this->assertEquals($this->mockPdo, $this->connection->getPdoInstance());
        $this->assertInstanceOf('\\PDO', $this->connection->getPdoInstance());
        $this->assertEquals('mysqlmock', $this->connection->getAdapter());
        $this->assertEquals(array('prefix' => 'cb_'), $this->connection->getAdapterConfig());
    }
}