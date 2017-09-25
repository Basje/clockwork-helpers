<?php namespace InThere\ClockworkHelpers;


use Clockwork\Request\Request;
use InThere\ClockworkHelpers\Contracts\SessionDataSourceInterface;

class SessionDataSource implements SessionDataSourceInterface
{
    private $data = [];

    public function resolve(Request $request)
    {
        $request->sessionData = $this->data;
    }

    public function setValue($name, $value, $default = null)
    {
        $value = is_null($value) ? $default : $value;
        $this->data[$name] = $value;
    }

    public function setInt($name, $value, $default = null)
    {
        // TODO: Implement setInt() method.
    }

    public function setArray($name, array $value, $default = null)
    {
        // TODO: Implement setArray() method.
    }

    public function setString($name, $value, $default = null)
    {
        // TODO: Implement setString() method.
    }

    public function setBool($name, $value, $default = null)
    {
        // TODO: Implement setBool() method.
    }

    public function setFloat($name, $value, $default = null)
    {
        // TODO: Implement setFloat() method.
    }

}