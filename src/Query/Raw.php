<?php namespace FairyDB\Query;

class Raw
{

    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $bindings;

    public function __construct($value, array $bindings = [])
    {
        $this->value = (string)$value;
        $this->bindings = $bindings;
    }

    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}