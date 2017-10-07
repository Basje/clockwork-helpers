<?php

namespace InThere\ClockworkHelpers\Illuminate;


use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Request;
use Illuminate\Database\Connection;
use InThere\ClockworkHelpers\Contracts\TimeLineDataSourceInterface;

class ConnectionDataSource implements DataSourceInterface
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var TimeLineDataSourceInterface
     */
    private $timeLineDataSource;

    /**
     * @var boolean
     */
    private $parseLog = false;

    /**
     * @var booleam
     */
    private $showTimeLine = true;

    /**
     * Constructor.
     *
     * @param Connection $connection
     * @param boolean $parse Replace placeholders with their bindings when set to `true`.
     */
    public function __construct(Connection $connection, TimeLineDataSourceInterface $dataSource)
    {
        $connection->enableQueryLog();
        var_dump($connection->getEventDispatcher());
        $connection->listen($this->getQueryTimeLineListener());
        var_dump($this->getQueryTimeLineListener());
        $this->connection = $connection;
        $this->timeLineDataSource = $dataSource;
    }

    /**
     * @param Request $request
     */
    public function resolve(Request $request)
    {
        $this->resolveQueryLog($request);
        $this->resolveTimeLine($request);
        var_dump($request);die;
    }

    public function enableQueryParsing()
    {
        $this->parseLog = true;
    }

    public function disableQueryParsing()
    {
        $this->parseLog = false;
    }

    public function enableQueryTimeLine()
    {
        $this->showTimeLine = true;
    }

    public function disableQueryTimeLine()
    {
        $this->showTimeLine = false;
    }

    private function resolveQueryLog(Request $request)
    {
        $log = $this->getQueryLog();
        if($this->parseLog)
        {
            $log = array_map(function($item){
                return [
                  'query' => $this->parseQueryData($item['query'], $item['bindings']),
                  'bindings' => $item['bindings'],
                  'time' => $item['time'],
                ];
            }, $log);
        }
        $request->databaseQueries = $this->convertLogToRequestFormat($log);
    }

    private function resolveTimeLine(Request $request)
    {
        if($this->showTimeLine)
        {
            $this->timeLineDataSource->resolve($request);
        }
    }

    /**
     * @return array
     */
    private function getQueryLog()
    {
        return $this->connection->getQueryLog();
    }

    /**
     * Parses a prepared query with placeholders and replaces those placeholders with their bindings. Use for display
     * purposes only!
     *
     * @param string $query
     * @param array $bindings
     *
     * @return string
     */
    private function parseQueryData($query, array $bindings)
    {
        foreach($bindings as $binding)
        {
            switch (gettype($binding)) {
                case 'string':
                    $query = $this->replaceFirstPlaceHolder(
                      $query,
                      sprintf(
                        "'%s'",
                        $this->escapeStringValue($binding)
                      )
                    );
                    break;
                case 'boolean':
                    $text = $binding ? 'true' : 'false';
                    $query = $this->replaceFirstPlaceHolder($query, $text);
                    break;
                default:
                    $query = $this->replaceFirstPlaceHolder($query, $binding);
                    break;
            }

        }
        return $query . ';';
    }

    /**
     * @param array $log
     *
     * @return array
     */
    private function convertLogToRequestFormat(array $log)
    {
        return array_map(function($data){
            return [
              'query' => $data['query'],
              'duration' => $data['time'],
            ];
        }, $log);
    }

    /**
     * Looks for the first question mark in a prepared query and replaces it with the given replacement value.
     *
     * @param string $query Prepared query with question mark placeholders.
     * @param string $replacement Value to replace the first placeholder with.
     *
     * @return string|null
     */
    private function replaceFirstPlaceHolder($query, $replacement)
    {
        return preg_replace('/\?/', (string)$replacement, $query, 1);
    }

    /**
     * Naive string escape function. Replaces single quotes with double single quotes as per the SQL specification. This
     * is not meant to be a safe function, but simply meant as a convenience function when displaying the text to screen.
     * Do not use this when you are actually running queries on a database!
     *
     * @param string $value
     *
     * @return string
     */
    private function escapeStringValue($value)
    {
        return str_replace("'", "''", $value);
    }

    private function getQueryTimeLineListener()
    {
        return function($sql, $bindings, $time){
            var_dump($sql, $bindings, $time);
            $now = microtime(true);
            /** @var string $sql */
            /** @var array $bindings */
            /** @var float $time */
            $this->timeLineDataSource->addEvent(
              sprintf('query_%f', $now),
              $sql,
              $now - $time,
              $now
            );
        };
    }

}