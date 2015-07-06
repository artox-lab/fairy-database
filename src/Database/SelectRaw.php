<?php namespace FairyDB\Database;


class SelectRaw extends Raw
{
    protected $alias;

    public function __construct($value, $alias = '', array $bindings = [])
    {
        parent::__construct($value, $bindings);

        $this->alias = (string)$alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }
}