<?php namespace Fairy\Connection\Drivers;

class Sqlite extends BaseDriver
{
    /**
     * @param $config
     *
     * @return mixed
     */
    public function doConnect($config)
    {
        $connectionString = 'sqlite:' . $config['database'];
        return new \PDO($connectionString, null, null, $config['options']);
    }
}