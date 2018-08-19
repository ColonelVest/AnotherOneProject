<?php

namespace FakeApiClient\Service;

use FakeApiClient\Model\User;
use HttpClient\HttpClient;
use HttpClient\Model\RequestOptions;
use HttpClient\Model\Uri;
use HttpClient\Model\Response;
use Psr\Log\LoggerInterface;

class FakeApiRequestor
{
    const USERS_ENDPOINT = '/users';

    /** @var HttpClient */
    protected $httpClient;
    protected $serializer;
    protected $logger;

    public function __construct(array $constHttpParams, Serializer $serializer, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->httpClient = $this->initFakeHttpClient($constHttpParams);
        $this->serializer = $serializer;
    }

    /**
     * @param int $userId
     * @return Response
     */
    public function getUser(int $userId)
    {
        return $this->httpClient->get(self::USERS_ENDPOINT . '/' . $userId, $this->getFakeApiHeaders());
    }

    /**
     * @param User $user
     * @return Response
     * @throws \ReflectionException
     */
    public function createUser(User $user)
    {
        $serializedUser = $this->serializer->serialize($user);

        return $this->httpClient->post(self::USERS_ENDPOINT, $serializedUser, $this->getFakeApiHeaders());
    }

    /**
     * @param User $user
     * @return Response
     * @throws \ReflectionException
     */
    public function updateUser(User $user)
    {
        $serializedUser = $this->serializer->serialize($user);
        $relativePath = self::USERS_ENDPOINT . '/' . $user->getId();

        return $this->httpClient->update($relativePath, $serializedUser, $this->getFakeApiHeaders());
    }

    /**
     * @param int $userId
     * @return Response
     */
    public function deleteUser(int $userId)
    {
        return $this->httpClient->get(self::USERS_ENDPOINT . '/' . $userId, $this->getFakeApiHeaders());
    }

    /**
     * @return array
     */
    protected function getFakeApiHeaders()
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * @param array $constHttpParams
     * @return HttpClient
     */
    protected function initFakeHttpClient(array $constHttpParams)
    {
        $fakeApiParams = [
            RequestOptions::SCHEME => Uri::DEFAULT_SCHEME,
            RequestOptions::HOST => 'localhost',
            RequestOptions::PORT => 81
        ];

        $clientParams = array_merge($constHttpParams, $fakeApiParams);

        return (new HttpClient($clientParams))
            ->setLogger($this->logger);
    }
}