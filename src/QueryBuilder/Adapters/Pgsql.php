<?php namespace FairyDB\QueryBuilder\Adapters;


class Pgsql extends BaseAdapter
{
    /**
     * @var string
     */
    protected $sanitizer = '"';
}