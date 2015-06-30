<?php namespace FairyDB\QueryBuilder\Adapters;

use FairyDB\QueryBuilder\Raw;

class Mysql extends BaseAdapter
{
    /**
     * @var string
     */
    protected $sanitizer = '`';

    public function wrapSanitizer($value)
    {
        // Its a raw query, just cast as string, object has __toString()
        if ($value instanceof Raw)
        {
            return (string)$value;
        }
        elseif ($value instanceof \Closure)
        {
            return $value;
        }

        return preg_replace('/(?i)\b((?!as)[a-z_]+)\b/', $this->sanitizer .'${1}' . $this->sanitizer, $value);
    }
}