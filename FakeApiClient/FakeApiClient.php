<?php

namespace FakeApiClient;

use FakeApiClient\Model\Address;
use FakeApiClient\Model\User;
use FakeApiClient\Service\FakeApiRequestor;
use FakeApiClient\Service\Serializer;
use HttpClient\Model\RequestOptions;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FakeApiClient
{
    /**
     * @throws \Exception
     */
    public function makeRequests()
    {
        $constHttpParam = [
            RequestOptions::THRESHOLD_CONST_PARAM => 5,
            RequestOptions::TIMEOUT_IN_SECONDS => 60
        ];
        $serializer = new Serializer();
        $logger = new Logger('fake');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../logs/fake_log.log', Logger::INFO));

        $requestor = new FakeApiRequestor($constHttpParam, $serializer, $logger);

        $response = $requestor->getUser(126);

        $user = $this->getTestUser();
        $response = $requestor->createUser($user);

        $user->setLast_name('Petrov1');
        $response = $requestor->updateUser($user);

        $response = $requestor->deleteUser($user->getId());
    }

    private function getTestUser()
    {
        $address = (new Address())
            ->setId(1)
            ->setCountry('Russia')
            ->setIso_code('ru')
            ->setCity('Moscow');

        return (new User())
            ->setId(2)
            ->setName('Test')
            ->setLast_name('Petrov')
            ->setAddress($address);
    }
}