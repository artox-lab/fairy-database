<?php namespace Fairy\Exceptions;

class DriverNotAllowedException extends Exception
{
    public function __construct($driver, array $allowed, $message = 'Connection driver %s not allowed. Allowed types: %s.')
    {
        parent::__construct(sprintf($message, $driver, implode(', ', $allowed)), 500);
    }
}