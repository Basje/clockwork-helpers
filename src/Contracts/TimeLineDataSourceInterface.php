<?php
/**
 * Created by PhpStorm.
 * User: Bas
 * Date: 25-9-2017
 * Time: 14:49
 */

namespace InThere\ClockworkHelpers\Contracts;


use Clockwork\DataSource\DataSourceInterface;

interface TimeLineDataSourceInterface extends DataSourceInterface
{
    public function startEvent($name, $description, $time = null, array $data = []);
    public function endEvent($name);
    public function addEvent($name, $description, $startTime, $endTime, array $data = []);
}