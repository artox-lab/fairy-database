<?php namespace FairyDB;

use FairyDB\ConnectionAdapters\BaseAdapter;
use FairyDB\QueryBuilder\QueryBuilder;

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
    protected $pdoInstance;

    /**
     * @var EventHandler
     */
    protected $eventHandler;

    /**
     * @param               $adapter
     * @param array         $adapterConfig
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

        $adapter = '\\FairyDB\\ConnectionAdapters\\' . ucfirst($this->adapter);

        /** @var BaseAdapter $adapterInstance */
        $adapterInstance = new $adapter();

        $pdo = $adapterInstance->connect($this->adapterConfig);
        $this->setPdoInstance($pdo);
    }

    /**
     * @param \PDO $pdo
     *
     * @return $this
     */
    public function setPdoInstance($pdo)
    {
        $this->pdoInstance = $pdo;
        return $this;
    }

    /**
     * @return \PDO
     */
    public function getPdoInstance()
    {
        return $this->pdoInstance;
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
}
