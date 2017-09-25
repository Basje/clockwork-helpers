<?php namespace InThere\ClockworkHelpers\Symfony;


use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseDataSource implements DataSourceInterface
{

    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function resolve(Request $request)
    {
        $request->responseTime = $this->getResponseTimestamp();
        $request->responseStatus = $this->getResponseStatusCode();
    }

    private function getResponseTimestamp()
    {
        return microtime(true);
    }

    private function getResponseStatusCode()
    {
        return $this->response->getStatusCode();
    }

}