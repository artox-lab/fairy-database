<?php namespace Fairy\Connection;

use Fairy\Connection\Drivers\BaseDriver;
use Fairy\Exceptions\DriverNotAllowedException;
use Fairy\Query\QueryBuilder;

define('WITH_ONE', 'with_one');
define('WITH_MANY', 'with_many');

class Connection
{
    /**
     * @var string
     */
    protected $driver;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \PDO
     */
    protected $pdo;

    /** @var EventsHandler */
    protected $eventsHandler;

    protected $transactionsCount = 0;

    public $queriesCount = 0;
    public $queriesTime = 0;

    protected $allowedDrivers = [
        'mysql',
        'pgsql',
        'sqlite'
    ];

    public function __construct($driver, $config)
    {
        $this->setDriver($driver)
            ->setConfig($config)
            ->connect();

        // Create event dependency
        $this->eventsHandler = new EventsHandler();
    }

    public function query()
    {
        return new QueryBuilder($this);
    }

    public function beginTransaction()
    {
        ++$this->transactionsCount;

        if ($this->transactionsCount == 1)
        {
            $this->pdo->beginTransaction();
        }
    }

    public function commit()
    {
        if ($this->transactionsCount == 1)
        {
            $this->pdo->commit();
        }

        --$this->transactionsCount;
    }

    public function rollBack()
    {
        if ($this->transactionsCount == 1)
        {
            $this->transactionsCount = 0;
            $this->pdo->rollBack();
        }
        else
        {
            --$this->transactionsCount;
        }
    }

    /**
     * @param \PDO $pdo
     *
     * @return $this
     */
    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
        return $this;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    public function setDriver($driver)
    {
        if (!in_array($driver, $this->allowedDrivers))
        {
            throw new DriverNotAllowedException($driver, $this->allowedDrivers);
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return EventsHandler
     */
    public function getEventHandler()
    {
        return $this->eventsHandler;
    }

    /**
     * Create the connection adapter
     */
    protected function connect()
    {
        // Build a database connection if we don't have one connected

        $driver = '\\Fairy\\Connection\\Drivers\\' . ucfirst($this->driver);

        /** @var BaseDriver $instance */
        $instance = new $driver();

        $pdo = $instance->connect($this->config);
        $this->setPdo($pdo);
    }
}
