<?php namespace InThere\ClockworkHelpers\Bridges\Symfony;


use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestDataSource implements DataSourceInterface
{

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * Constructor.
     *
     * @param HttpRequest $httpRequest
     */
    public function __construct(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param Request $request
     */
    public function resolve(Request $request)
    {
        $request->time     = $this->getRequestTime();
        $request->method   = $this->getRequestMethod();
        $request->uri      = $this->getRequestUri();
        $request->headers  = $this->getRequestHeaders();
        $request->getData  = $this->getGetData();
        $request->postData = $this->getPostData();
        $request->cookies  = $this->getCookies();
    }

    /**
     * @return integer Unix time stamp representing the time of the request.
     */
    private function getRequestTime()
    {
        return $this->httpRequest->server->getInt('REQUEST_TIME');
    }

    /**
     * @return string Request method.
     */
    private function getRequestMethod()
    {
        return $this->httpRequest->getMethod();
    }

    /**
     * @return string Request URI.
     */
    private function getRequestUri()
    {
        return $this->httpRequest->getRequestUri();
    }

    /**
     * @return array All the request headers.
     */
    private function getRequestHeaders()
    {
        return $this->httpRequest->headers->all();
    }

    /**
     * @return array Request GET data.
     */
    private function getGetData()
    {
        // Maybe find a better way to determine GET data?
        if($this->getRequestMethod() === 'GET')
        {
            return $this->httpRequest->request->all();
        }
        return [];
    }

    /**
     * @return array Request POST data.
     */
    private function getPostData()
    {
        // Maybe find a better way to determine POST data?
        if($this->getRequestMethod() === 'POST')
        {
            return $this->httpRequest->request->all();
        }
        return [];
    }

    /**
     * @return array Request cookies.
     */
    private function getCookies()
    {
        return $this->httpRequest->cookies->all();
    }

}