<?php namespace Fairy\Tests;

use Fairy\DB;

class DbTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    const TEST_FIXTURES_DIR = __DIR__ . '/../../tests/Fixtures';

    protected $fixtures = [];

    protected static $connection;
    protected static $pdo;

    protected static $db;

    protected $config = [
        'adapter' => null,
        'host' => null,
        'port' => null,
        'database' => null,
        'username' => null,
        'password' => null,
        'charset' => null,
        'collation' => null,
        'prefix' => ''
    ];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bindConfig();
    }

    public function getConnection()
    {
        if (empty(self::$connection))
        {
            $pdo = $this->pdo();

            self::$connection = $this->createDefaultDBConnection($pdo, $this->config['database']);
        }

        return self::$connection;
    }

    protected function getDataSet(array $fixtures = [])
    {
        if (empty($this->fixtures) && empty($fixtures))
        {
            throw new \Exception('Fixtures not specified.');
        }

        if (empty($fixtures))
        {
            $fixtures = $this->fixtures;
        }

        $arrayDataSet = [];

        foreach ($fixtures as $fixture)
        {
            $fixtureFilename = (file_exists(static::TEST_FIXTURES_DIR . '/' . $fixture . '.php')) ? static::TEST_FIXTURES_DIR . '/' . $fixture . '.php' : static::TEST_FIXTURES_DIR . '/' . $fixture . '/index.php';

            if (file_exists($fixtureFilename))
            {
                $dataSetTables = require($fixtureFilename);

                if (empty($dataSetTables))
                {
                    throw new \Exception('Fixture "' . $fixture . '" is empty.');
                }

                foreach ($dataSetTables as $table => $data)
                {
                    $arrayDataSet[$this->config['prefix'] . $table] = $data;
                }
            }
            else
            {
                throw new \Exception('Fixture "' . $fixture . '" not found."');
            }
        }

        return $this->createArrayDataSet($arrayDataSet);
    }

    protected function db()
    {
        if (empty(self::$db))
        {
            self::$db = new DB($this->config['adapter'], $this->config);
        }

        return self::$db;
    }

    protected function pdo()
    {
        if (empty(self::$pdo))
        {
            $connectionString =  $this->config['adapter'] . ':host=' . $this->config['host'] . ';port=' . $this->config['port'] . ';dbname=' . $this->config['database'] . ';charset=' . $this->config['charset'] . ';';

            if (isset($config['unixSocket']))
            {
                $connectionString .= ';unix_socket=' . $config['unixSocket'];
            }

            self::$pdo = new \PDO(
                $connectionString,
                $this->config['username'],
                $this->config['password'],
                [
                    \PDO::ATTR_PERSISTENT => true
                ]
            );

            self::$pdo->query('SET NAMES ' . $this->config['charset'] . ';');
        }

        return self::$pdo;
    }

    protected function bindConfig()
    {
        $this->config = [
            'adapter' => $GLOBALS['DB_ADAPTER'],
            'host' => $GLOBALS['DB_HOST'],
            'port' => $GLOBALS['DB_PORT'],
            'database' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USERNAME'],
            'password' => $GLOBALS['DB_PASSWORD'],
            'charset' => $GLOBALS['DB_CHARSET'],
            'collation' => $GLOBALS['DB_COLLATION'],
            'prefix' => $GLOBALS['DB_TABLE_PREFIX'],
            'unixSocket' => $GLOBALS['DB_UNIX_SOCKET']
        ];
    }
}