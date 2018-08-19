<?php

namespace HttpClient;

use HttpClient\Model\Request;
use HttpClient\Model\RequestOptions;
use HttpClient\Model\Response;
use HttpClient\Model\Uri;
use Psr\Log\LoggerInterface;

class HttpClient
{
    const DEFAULT_TIMEOUT = 60;

    /** @var LoggerInterface */
    protected $logger;

    /** @var array */
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param LoggerInterface $logger
     * @return HttpClient
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendByRequest(Request $request)
    {
        $curlResource = $this->compileCurlResource($request);
        $output = curl_exec($curlResource);

        $response = new Response($curlResource, $output);
        $this->logRequestIfExceedThreshold($request, $response);

        curl_close($curlResource);

        return $response;
    }

    /**
     * @param string $relativePath
     * @param $requestData
     * @param array $headers
     * @return Response
     */
    public function post(string $relativePath, $requestData, array $headers = [])
    {
        return $this->sendByRequest($this->createRequest(Request::POST_METHOD, $relativePath, $requestData, $headers));
    }

    /**
     * @param string $relativePath
     * @param $requestData
     * @param array $headers
     * @return Response
     */
    public function get(string $relativePath, $requestData = null, array $headers = [])
    {
        return $this->sendByRequest($this->createRequest(Request::GET_METHOD,$relativePath, $requestData, $headers));
    }

    /**
     * @param string $relativePath
     * @param $requestData
     * @param array $headers
     * @return Response
     */
    public function update(string $relativePath, $requestData, array $headers = [])
    {
        return $this->sendByRequest(
            $this->createRequest(Request::UPDATE_METHOD, $relativePath, $requestData, $headers)
        );
    }

    /**
     * @param string $relativePath
     * @param $requestData
     * @param array $headers
     * @return Response
     */
    public function delete(string $relativePath, $requestData, array $headers = [])
    {
        return $this->sendByRequest(
            $this->createRequest(Request::DELETE_METHOD, $relativePath, $requestData, $headers)
        );
    }

    /**
     * @param string $method
     * @param string $relativePath
     * @param $requestData
     * @param array $headers
     * @return Request
     */
    protected function createRequest(string $method, string $relativePath, $requestData, array $headers = [])
    {
        $uri = $this->compileUri($relativePath);

        return (new Request($method, $uri, $headers, $requestData));
    }

    /**
     * @param string $relativePath
     * @return string
     */
    protected function compileUri(string $relativePath)
    {
        $scheme = $this->options[RequestOptions::SCHEME] ?? Uri::DEFAULT_SCHEME;
        $host = $this->options[RequestOptions::HOST] ?? Uri::DEFAULT_HOST;
        $port = $this->options[RequestOptions::PORT] ?? Uri::DEFAULT_PORT;

        return (new Uri($host, $relativePath, $port, $scheme))->compileUri();
    }

    /**
     * @param Request $request
     * @return resource
     */
    protected function compileCurlResource(Request $request)
    {
        $curlRequest = curl_init();

        if ($request->getMethod() == Request::POST_METHOD) {
            curl_setopt($curlRequest, CURLOPT_POST, 1);
        } elseif (in_array($request->getMethod(), [Request::UPDATE_METHOD, Request::DELETE_METHOD])) {
            curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        }

        if (!empty($request->getHeaders())) {
            curl_setopt($curlRequest, CURLOPT_HTTPHEADER, $request->compileHeaders());
        }

        $requestUri = $request->getUri();
        if (!empty($request->getRequestData())) {
            if (in_array($request->getMethod(), [Request::UPDATE_METHOD, Request::POST_METHOD])) {
                curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $request->getRequestData());
            } elseif ($request->getMethod() === 'GET' && !is_null($request->getRequestData())) {
                $requestUri .=  '?' . http_build_query($request->getRequestData());
            }
        }

        curl_setopt($curlRequest, CURLOPT_URL, $requestUri);

        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlRequest, CURLINFO_HEADER_OUT, 1);
        curl_setopt($curlRequest, CURLOPT_VERBOSE, 1);

        $timeout = $this->options[RequestOptions::TIMEOUT_IN_SECONDS] ?? self::DEFAULT_TIMEOUT;
        curl_setopt($curlRequest,CURLOPT_TIMEOUT,$timeout);

        return $curlRequest;
    }

    protected function logRequestIfExceedThreshold(Request $request, Response $response)
    {
        if ($this->logger instanceof LoggerInterface
            && isset($this->options[RequestOptions::THRESHOLD_CONST_PARAM])
            && $response->getRequestTime() > $this->options[RequestOptions::THRESHOLD_CONST_PARAM]
        ) {
            $warningMessage = 'Too long('
                . $response->getRequestTime()
                . ')s '
                . $request->getMethod()
                . ' request to '
                . $request->getUri();

            $this->logger->warning($warningMessage);
        }
    }
}