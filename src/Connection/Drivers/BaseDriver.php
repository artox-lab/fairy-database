<?php namespace Fairy\Connection\Drivers;

abstract class BaseDriver
{
    /**
     * @param $config
     *
     * @return \PDO
     */
    public function connect($config)
    {
        if (!isset($config['options']))
        {
            $config['options'] = [];
        }
        return $this->doConnect($config);
    }

    /**
     * @param $config
     *
     * @return mixed
     */
    abstract protected function doConnect($config);
}