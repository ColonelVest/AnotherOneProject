<?php

namespace HttpClient\Model;

class Uri
{
    const DEFAULT_SCHEME = 'http';
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = 80;

    /** @var string */
    private $scheme;
    /** @var  */
    private $host;
    /** @var  */
    private $port;
    private $relativePath;

    public function __construct(
        string $host = self::DEFAULT_HOST,
        string $relativePath = '',
        int $port = self::DEFAULT_PORT,
        string $scheme = self::DEFAULT_SCHEME
    ) {
        $this->host = $host;
        $this->relativePath = $relativePath;
        $this->scheme = $scheme;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function compileUri()
    {

        $url =  $this->scheme
            . '://'
            . $this->host
            . ':'
            . $this->port
            . $this->relativePath;

        return $url;
    }
}