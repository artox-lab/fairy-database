<?php namespace Fairy\Connection\Drivers;

class Mysql extends BaseDriver
{
    /**
     * @param $config
     *
     * @return mixed
     */
    protected function doConnect($config)
    {
        $connectionString = 'mysql:host=' . $config['host'] . ';dbname=' . $config['database'];

        if (!empty($config['port']))
        {
            $connectionString .= ';port=' . $config['port'];
        }

        if (!empty($config['unixSocket']))
        {
            $connectionString .= ';unix_socket=' . $config['unixSocket'];
        }

        $connection = new \PDO($connectionString, $config['username'], $config['password'], $config['options']);

        if (isset($config['charset']))
        {
            $connection->prepare('SET NAMES "' . $config['charset'] . '"')->execute();
        }

        return $connection;
    }
}