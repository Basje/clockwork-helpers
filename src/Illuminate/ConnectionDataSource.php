<?php

namespace InThere\ClockworkHelpers\Illuminate;


use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Request;
use Illuminate\Database\Connection;

class ConnectionDataSource implements DataSourceInterface
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var boolean
     */
    private $parseLog = false;

    /**
     * Constructor.
     *
     * @param Connection $connection
     * @param boolean $parse Replace placeholders with their bindings when set to `true`.
     */
    public function __construct(Connection $connection, $parse = false)
    {
        $connection->enableQueryLog();
        $this->connection = $connection;
        $this->parseLog = $parse === true;
    }

    /**
     * @param Request $request
     */
    public function resolve(Request $request)
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

}