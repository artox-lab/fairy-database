<?php namespace FairyDB\QueryBuilder;

class ResultsProcessor 
{
    protected $simpleResult = false;
    protected $counters = [];
    protected $indexes = [];
    protected $schemas = [];

    public function columnsCollector($columns)
    {
        $arr = [];

        if (empty($columns))
        {
            return $arr;
        }

        foreach ($columns as $key => $info)
        {
            if (!is_numeric($key) && is_array($info))
            {
                $prefix = $key;

                if (!empty($info))
                {
                    foreach ($info as $columnKey => $column)
                    {
                        if ($columnKey === WITH_MANY || $columnKey === WITH_ONE)
                        {
                            $arr = array_merge($arr, $this->columnsCollector($column));
                        }
                        else
                        {
                            $arr[(is_integer($columnKey) ? $prefix . '.' . $column : $columnKey)] = $prefix . '___' . $column;
                        }
                    }
                }
            }
            else
            {
                $arr[$key] = $info;
            }
        }

        return $arr;
    }

    public function processResult(QueryBuilder $builder, $rows)
    {
        $results = [];

        if (empty($rows))
        {
            return $results;
        }

        $this->init($builder->getSelect());

        if (!$this->simpleResult)
        {
            while (!empty($rows))
            {
                $row = array_shift($rows);

                $results = $this->process($builder->getSelect(), $results, $this->getEntitiesFromRow($row), $this->indexes, $this->counters);
            }
        }
        else
        {
            $results = $rows;
        }

        return $results;
    }

    protected function init($select, $walk = false)
    {
        if (empty($select))
        {
            return false;
        }

        foreach ($select as $fieldKey => $info)
        {
            if (is_array($info) && !empty($info))
            {
                $this->schemas[$fieldKey] = [];

                foreach ($info as $attribute)
                {
                    if (is_array($attribute))
                    {
                        continue;
                    }

                    $this->schemas[$fieldKey][$fieldKey . '___' . $attribute] = $attribute;
                }

                $this->simpleResult = false;

                if (!$walk)
                {
                    $this->counters[$fieldKey]['counter'] = -1;
                }

                if (!empty($info[WITH_MANY]))
                {
                    $this->init($info[WITH_MANY], true);
                }

                if (!empty($info[WITH_ONE]))
                {
                    $this->init($info[WITH_ONE], true);
                }
            }
        }

        return true;
    }

    protected function getEntitiesFromRow(array $row)
    {
        if (empty($row) || empty($this->schemas))
        {
            return [];
        }

        $entities = [];

        foreach ($this->schemas as $name => $schema)
        {
            $attributes = array_intersect_key($row, $schema);
            $fields = array_values($schema);

            $entities[$name] = array_combine($fields, $attributes);
        }

        return $entities;
    }

    protected function process(array $select, array &$results, array $entities, array &$indexes, array &$counters, $relationType = false)
    {
        if (!empty($select))
        {
            foreach ($select as $name => $schema)
            {
                if (is_array($schema))
                {
                    if (!isset($indexes[$name][$entities[$name]['id']]))
                    {
                        if (!isset($counters[$name]['counter']))
                        {
                            $counters[$name]['counter'] = -1;
                        }

                        $counters[$name]['counter']++;

                        if (!$relationType)
                        {
                            $results[$counters[$name]['counter']] = $entities[$name];
                        }
                        else
                        {
                            if ($relationType === WITH_ONE)
                            {
                                $results[$name] = $entities[$name];
                            }
                            else
                            {
                                $results[$name][$counters[$name]['counter']] = $entities[$name];
                            }
                        }

                        $indexes[$name][$entities[$name]['id']]['index'] = $counters[$name]['counter'];
                        $counters[$name][$entities[$name]['id']] = [];
                    }

                    if (in_array('id', $schema))
                    {
                        if (!empty($schema[WITH_MANY]))
                        {
                            if (!$relationType)
                            {
                                $this->process(
                                    $schema[WITH_MANY],
                                    $results[$indexes[$name][$entities[$name]['id']]['index']],
                                    $entities,
                                    $indexes[$name][$entities[$name]['id']],
                                    $counters[$name][$entities[$name]['id']],
                                    WITH_MANY
                                );
                            }
                            else
                            {
                                if ($relationType === WITH_MANY)
                                {
                                    $this->process(
                                        $schema[WITH_MANY],
                                        $results[$name][$indexes[$name][$entities[$name]['id']]['index']],
                                        $entities,
                                        $indexes[$name][$entities[$name]['id']],
                                        $counters[$name][$entities[$name]['id']],
                                        WITH_MANY
                                    );
                                }
                                else
                                {
                                    $this->process(
                                        $schema[WITH_MANY],
                                        $results[$name],
                                        $entities,
                                        $indexes[$name][$entities[$name]['id']],
                                        $counters[$name][$entities[$name]['id']],
                                        WITH_MANY
                                    );
                                }
                            }
                        }

                        if (!empty($schema[WITH_ONE]))
                        {
                            if (!$relationType)
                            {
                                $this->process(
                                    $schema[WITH_ONE],
                                    $results[$indexes[$name][$entities[$name]['id']]['index']],
                                    $entities,
                                    $indexes[$name][$entities[$name]['id']],
                                    $counters[$name][$entities[$name]['id']],
                                    WITH_ONE
                                );
                            }
                            else
                            {
                                if ($relationType === WITH_MANY)
                                {
                                    $this->process(
                                        $schema[WITH_ONE],
                                        $results[$name][$indexes[$name][$entities[$name]['id']]['index']],
                                        $entities,
                                        $indexes[$name][$entities[$name]['id']],
                                        $counters[$name][$entities[$name]['id']],
                                        WITH_ONE
                                    );
                                }
                                else
                                {
                                    $this->process(
                                        $schema[WITH_ONE],
                                        $results[$name],
                                        $entities,
                                        $indexes[$name][$entities[$name]['id']],
                                        $counters[$name][$entities[$name]['id']],
                                        WITH_ONE
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }


        return $results;
    }
}