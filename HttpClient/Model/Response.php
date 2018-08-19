<?php

namespace HttpClient\Model;

class Response
{
    /** @var int */
    private $statusCode;
    /** @var float */
    private $requestTime;
    /** @var string */
    private $body;

    public function __construct($curlResource, $curlResponse)
    {
        $this->body = $curlResponse;
        $this->extractRequestData($curlResource);
    }

    /**
     * @param $curlResource
     */
    protected function extractRequestData($curlResource)
    {
        $this->statusCode = curl_getinfo($curlResource,CURLINFO_HTTP_CODE);
        $this->requestTime = curl_getinfo($curlResource, CURLINFO_TOTAL_TIME);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return float
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }
}