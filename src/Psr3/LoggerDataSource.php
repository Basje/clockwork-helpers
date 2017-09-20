<?php namespace InThere\ClockworkHelpers\Psr3;


use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Log;
use Clockwork\Request\Request;
use Psr\Log\LoggerInterface;

class LoggerDataSource implements DataSourceInterface, LoggerInterface
{

    /**
     * @var Log
     */
    private $log;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->log = new Log();
    }

    /**
     * @param Request $request
     */
    public function resolve(Request $request)
    {
        $request->log = $this->log->toArray();
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function emergency($message, array $context = [])
    {
        $this->log->emergency($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context = [])
    {
        $this->log->alert($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context = [])
    {
        $this->log->critical($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context = [])
    {
        $this->log->error($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context = [])
    {
        $this->log->warning($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context = [])
    {
        $this->log->notice($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->log->info($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->log->debug($message, $context);
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $this->log->log($level, $message, $context);
    }

}