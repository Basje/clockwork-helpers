<?php namespace InThere\ClockworkHelpers\Exceptions;


class InvalidBoolException extends InvalidTypeException
{

    protected function getExpectedType()
    {
        return 'integer';
    }

}