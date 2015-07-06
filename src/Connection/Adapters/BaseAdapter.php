<?php namespace Fairy\Connection\Adapters;

abstract class BaseAdapter
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