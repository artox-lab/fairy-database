<?php namespace FairyDB\Database;


class NestedCriteria extends QueryBuilder
{
    /**
     * @param        $key
     * @param null   $operator
     * @param null   $value
     * @param string $joiner
     *
     * @return $this
     */
    protected function _where($key, $operator = null, $value = null, $joiner = 'AND')
    {
        $key = $this->addTablePrefix($key);
        $this->statements['criteria'][] = compact('key', 'operator', 'value', 'joiner');
        return $this;
    }
}