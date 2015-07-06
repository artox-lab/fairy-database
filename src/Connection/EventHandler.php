<?php namespace Fairy\Connection;

use Fairy\Query\QueryBuilder;
use Fairy\Query\Raw;

class EventHandler
{
    /**
     * @var array
     */
    protected $events = array();

    /**
     * @var array
     */
    protected $firedEvents = array();

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param $event
     * @param $table
     *
     * @return callable|null
     */
    public function getEvent($event, $table = ':any')
    {
        if ($table instanceof Raw) {
            return null;
        }
        return isset($this->events[$table][$event]) ? $this->events[$table][$event] : null;
    }

    /**
     * @param          $event
     * @param string   $table
     * @param callable $action
     *
     * @return void
     */
    public function registerEvent($event, $table = ':any', \Closure $action)
    {
        $this->events[$table][$event] = $action;
    }

    /**
     * @param          $event
     * @param string   $table
     *
     * @return void
     */
    public function removeEvent($event, $table = ':any')
    {
        unset($this->events[$table][$event]);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param                     $event
     * @return mixed
     */
    public function fireEvents($queryBuilder, $event) {
        $statements = $queryBuilder->getStatements();
        $tables = isset($statements['tables']) ? $statements['tables'] : array();

        // Events added with :any will be fired in case of any table,
        // we are adding :any as a fake table at the beginning.
        array_unshift($tables, ':any');

        // Fire all events
        foreach ($tables as $table) {
            // Fire before events for :any table
            if ($action = $this->getEvent($event, $table)) {
                // Make an event id, with event type and table
                $eventId = $event . $table;

                // Fire event
                $handlerParams = func_get_args();
                unset($handlerParams[1]); // we do not need $event
                // Add to fired list
                $this->firedEvents[] = $eventId;

                switch(count($handlerParams))
                {
                    case 0:
                        $action();
                        break;

                    case 1:
                        $action($handlerParams[0]);
                        break;

                    case 2:
                        $action($handlerParams[0], $handlerParams[1]);
                        break;

                    case 3:
                        $action($handlerParams[0], $handlerParams[1], $handlerParams[2]);
                        break;

                    case 4:
                        $action($handlerParams[0], $handlerParams[1], $handlerParams[2]);
                        break;

                    case 5:
                        $action($handlerParams[0], $handlerParams[1], $handlerParams[2]);
                        break;
                }

                $result = call_user_func_array($action, $handlerParams);


                if (!is_null($result)) {
                    return $result;
                };
            }
        }
    }
}