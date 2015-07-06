<?php namespace Fairy\Query\Adapters;

use Fairy\Query\Raw;
use Fairy\Query\SelectRaw;

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
            if ($value instanceof SelectRaw)
            {
                $value = (string)$value . ' AS ' . $this->sanitizer . $value->getAlias() . $this->sanitizer;
            }

            return (string)$value;
        }
        elseif ($value instanceof \Closure)
        {
            return $value;
        }

        return preg_replace('/(?i)\b((?!as)[a-z_0-9]+)\b/', $this->sanitizer . '${1}' . $this->sanitizer, $value);
    }
}