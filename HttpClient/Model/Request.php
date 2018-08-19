<?php

namespace HttpClient\Model;

class Request
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';
    const UPDATE_METHOD = 'UPDATE';
    const DELETE_METHOD = 'DELETE';

    /** @var string */
    protected $method = self::GET_METHOD;
    /** @var array  */
    protected $headers = [];
    /** @var string */
    protected $uri;
    protected $requestData;

    public function __construct(
        string $method,
        string $uri,
        array $headers = [],
        $body = null
    ) {
        $this
            ->setMethod(strtoupper($method))
            ->setUri($uri)
            ->setHeaders($headers);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Request
     */
    public function setMethod(string $method): Request
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $headerName => $headerValue) {
            if (!is_string($headerName) || !is_string($headerValue)) {
                throw new \InvalidArgumentException('Argument "headers" must be an associative array');
            }

            $this->addHeader($headerName, $headerValue);
        }

        return $this;
    }

    /**
     * @param $headerName
     * @param $headerValue
     * @return Request
     */
    public function addHeader(string $headerName, string $headerValue)
    {
        $this->headers[$headerName] = $headerValue;

        return $this;
    }

    /**
     * @return array
     */
    public function compileHeaders()
    {
        $compilerHeaders = [];
        foreach ($this->getHeaders() as $headerName => $headerValue) {
            $compilerHeaders[] = "$headerName: $headerValue";
        }

        return $compilerHeaders;
    }

    /**
     * @return string
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return Request
     */
    public function setUri(string $uri): Request
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * @param mixed $requestData
     * @return Request
     */
    public function setRequestData($requestData)
    {
        $this->requestData = $requestData;

        return $this;
    }
}