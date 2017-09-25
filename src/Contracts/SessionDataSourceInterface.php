<?php namespace InThere\ClockworkHelpers\Contracts;


use Clockwork\DataSource\DataSourceInterface;

interface SessionDataSourceInterface extends DataSourceInterface
{
    public function setValue($name, $value, $default = null);
    public function setInt($name, $value, $default = null);
    public function setArray($name, array $value, $default = null);
    public function setString($name, $value, $default = null);
    public function setBool($name, $value, $default = null);
    public function setFloat($name, $value, $default = null);
}