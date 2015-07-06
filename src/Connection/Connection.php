<?php namespace Fairy\Connection;

use Fairy\Connection\Adapters\BaseAdapter;
use Fairy\Query\QueryBuilder;

define('WITH_ONE', 'with_one');
define('WITH_MANY', 'with_many');

class Connection
{
    /**
     * @var string
     */
    protected $adapter;

    /**
     * @var array
     */
    protected $adapterConfig;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var EventHandler
     */
    protected $eventHandler;

    protected $transactionsCount = 0;

    /**
     * @param               $adapter
     * @param array $adapterConfig
     */
    public function __construct($adapter, array $adapterConfig)
    {
        $this->setAdapter($adapter)
            ->setAdapterConfig($adapterConfig)
            ->connect();

        // Create event dependency
        $this->eventHandler = new EventHandler();
    }

    /**
     * Returns an instance of Query Builder
     */
    public function getQueryBuilder()
    {
        return new QueryBuilder($this);
    }


    /**
     * Create the connection adapter
     */
    protected function connect()
    {
        // Build a database connection if we don't have one connected

        $adapter = '\\Fairy\\Connection\\Adapters\\' . ucfirst($this->adapter);

        /** @var BaseAdapter $adapterInstance */
        $adapterInstance = new $adapter();

        $pdo = $adapterInstance->connect($this->adapterConfig);
        $this->setPdo($pdo);
    }

    /**
     * @param \PDO $pdo
     *
     * @return $this
     */
    public function setPdo($pdo)
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

    /**
     * @param $adapter
     *
     * @return $this
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param array $adapterConfig
     *
     * @return $this
     */
    public function setAdapterConfig(array $adapterConfig)
    {
        $this->adapterConfig = $adapterConfig;
        return $this;
    }

    /**
     * @return array
     */
    public function getAdapterConfig()
    {
        return $this->adapterConfig;
    }

    /**
     * @return EventHandler
     */
    public function getEventHandler()
    {
        return $this->eventHandler;
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

    public function query()
    {
        return new QueryBuilder($this);
    }
}
