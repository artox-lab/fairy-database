<?php namespace FairyDB\ConnectionAdapters;

class Mysql extends BaseAdapter
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

        if (isset($config['unix_socket']))
        {
            $connectionString .= ';unix_socket=' . $config['unix_socket'];
        }

        $connection = new \PDO($connectionString, $config['username'], $config['password'], $config['options']);

        if (isset($config['charset']))
        {
            $connection->prepare('SET NAMES "' . $config['charset'] . '"')->execute();
        }

        return $connection;
    }
}