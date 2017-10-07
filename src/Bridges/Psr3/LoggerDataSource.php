<?php namespace InThere\ClockworkHelpers\Psr3;


use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Log;
use Clockwork\Request\Request;
use Psr\Log\LoggerInterface;

class LoggerDataSource extends Log implements DataSourceInterface, LoggerInterface
{

    /**
     * @param Request $request
     */
    public function resolve(Request $request)
    {
        $request->log = $this->toArray();
    }

}