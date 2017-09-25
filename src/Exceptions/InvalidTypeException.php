<?php namespace InThere\ClockworkHelpers\Exceptions;

use InThere\ClockworkHelpers\Exception;

abstract class InvalidTypeException extends Exception
{

    /**
     * Constructor.
     *
     * @param mixed $value
     * @param \Exception|null $previous
     */
    public function __construct($value, \Exception $previous = null)
    {
        $message = sprintf(
          'Expected %s value but got `%s` of type `%s` instead',
          $this->getExpectedType(),
          $value,
          gettype($value)
        );
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string Name of expected type.
     */
    protected abstract function getExpectedType();
}