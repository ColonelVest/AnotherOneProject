<?php

namespace FakeApiClient\Service;

//Здесь должен был быть нормальный сериализатор, но для тестового, пожалуй, будет излишне.
class Serializer
{
    /**
     * @param $object
     * @return string
     * @throws \ReflectionException
     */
    public function serialize($object)
    {
        return json_encode($this->normalize($object));
    }

    /**
     * @param $object
     * @return array
     * @throws \ReflectionException
     */
    public function normalize($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('argument "object" must be type of object');
        }

        $normalizedData = [];
        $reflectionClass = new \ReflectionClass(get_class($object));
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();

            $method = 'get' . ucfirst($property->getName());
            $propertyValue = $object->$method();
            if (is_object($propertyValue)) {
                $propertyValue = $this->normalize($propertyValue);
            }

            $normalizedData[$propertyName] = $propertyValue;
        }

        return $normalizedData;
    }
}