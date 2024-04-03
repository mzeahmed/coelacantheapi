<?php

declare(strict_types=1);

namespace App\Core\Serializer;

use Doctrine\Common\Collections\Collection;

class Serializer
{
    private array $excludedProperties = [];

    public function setExcludedProperties(array $excludedProperties = []): void
    {
        $this->excludedProperties = $excludedProperties;
    }

    /**
     * Serialize objects to access properties without getters
     *
     * @param array $objects The objects to serialize
     *
     * @return array The serialized objects
     */
    public function serialize(array $objects): array
    {
        $data = [];

        foreach ($objects as $object) {
            $reflectionClass = new \ReflectionClass(get_class($object));
            $properties = $reflectionClass->getProperties();

            $serializedObject = [];

            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $property->setAccessible(true);

                if (in_array($propertyName, $this->excludedProperties, true)) {
                    continue;
                }

                // Relations handling
                if ($property->getValue($object) instanceof Collection) {
                    // Seralizing linked objects
                    $serializedObject[$propertyName] = $this->serialize($property->getValue($object)->toArray());
                } else {
                    $serializedObject[$propertyName] = $property->getValue($object);
                }
            }

            $data[] = $serializedObject;
        }

        return $data;
    }
}
