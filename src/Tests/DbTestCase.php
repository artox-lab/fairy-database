<?php namespace FairyDB\Tests;

class DbTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    const TEST_FIXTURES_DIR = __DIR__ . '/../../../tests/Fixtures';

    protected $fixtures = [];

    protected $connection;

    protected $config = [
        'dsn' => null,
        'db' => null,
        'username' => null,
        'password' => null,
        'charset' => null,
    ];

    protected $tablePrefix;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->config = [
            'host' => $GLOBALS['DB_HOST'],
            'port' => $GLOBALS['DB_PORT'],
            'db' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USERNAME'],
            'password' => $GLOBALS['DB_PASSWORD'],
            'charset' => $GLOBALS['DB_CHARSET'],
        ];

        $this->tablePrefix = $GLOBALS['DB_TABLE_PREFIX'];
    }

    public function getConnection()
    {
        if (empty($this->connection))
        {
            $pdo = new \PDO(
                'mysql:host=' . $this->config['host'] . ';port=' . $this->config['port'] . ';dbname=' . $this->config['db'] . ';charset=' . $this->config['charset'] . ';',
                $this->config['username'],
                $this->config['password'],
                [
                    \PDO::ATTR_PERSISTENT => true
                ]
            );

            $pdo->query('SET NAMES ' . $this->config['charset'] . ';');

            $this->connection = $this->createDefaultDBConnection($pdo, $this->config['db']);
        }

        return $this->connection;
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
            $fixtureFilename = (file_exists(self::TEST_FIXTURES_DIR . '/' . $fixture . '.php')) ? self::TEST_FIXTURES_DIR . '/' . $fixture . '.php' : self::TEST_FIXTURES_DIR . '/' . $fixture . '/index.php' ;

            if (file_exists($fixtureFilename))
            {
                $dataSetTables = require($fixtureFilename);

                if (empty($dataSetTables))
                {
                    throw new \Exception('Fixture "' . $fixture . '" is empty.');
                }

                foreach ($dataSetTables as $table => $data)
                {
                    $arrayDataSet[$this->tablePrefix . $table] = $data;
                }
            }
            else
            {
                throw new \Exception('Fixture "' . $fixture . '" not found."');
            }
        }

        print_r($arrayDataSet);

        return $this->createArrayDataSet($arrayDataSet);
    }
}