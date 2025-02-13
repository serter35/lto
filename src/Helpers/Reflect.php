<?php

namespace SerterSerbest\LTO\Helpers;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

class Reflect
{
    private ReflectionClass $reflection;

    private object $context;

    private int $propertyFilterFlag = ReflectionProperty::IS_PUBLIC;

    public function __construct(object|string $objectOrClass)
    {
        $this->context = static::createContext($objectOrClass);
        $this->reflection = new ReflectionClass($objectOrClass);
    }

    private static function createContext(object|string $objectOrClass): object
    {
        return is_string($objectOrClass)
            ? (new ReflectionClass($objectOrClass))->newInstanceWithoutConstructor()
            : $objectOrClass;
    }

    public function getClass(): ReflectionClass
    {
        return $this->reflection;
    }

    public function getConstructorParams(): array
    {
        return $this->reflection->getConstructor()->getParameters();
    }

    public function getAttributes(): array
    {
        return $this->reflection->getAttributes();
    }

    public function findAttribute(\Closure $closure): ?ReflectionAttribute
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($closure($attribute))
                return $attribute;
        }

        return null;
    }

    public function invokeMethod(string $methodName, array $args = []): mixed
    {
        return $this->reflection->getMethod($methodName)->invokeArgs($this->context, $args);
    }

    /**
     * @return array<ReflectionProperty>
     */
    public function getProperties(): array
    {
        return $this->reflection->getProperties($this->propertyFilterFlag);
    }

    public function getProperty(string $name): ?ReflectionProperty
    {
        try {
            return $this->reflection->getProperty($name);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getPropertyValue(string $name): mixed
    {
        return $this->getProperty($name)?->getValue($this->context);
    }

    /**
     * @param string $name
     * @return array<ReflectionAttribute>
     */
    public function getPropertyAttributes(string $name): array
    {
        return $this->getProperty($name)?->getAttributes() ?? [];
    }

    public function findPropertyAttribute(string $propertyName, \Closure $closure): ?ReflectionAttribute
    {
        foreach ($this->getPropertyAttributes($propertyName) as $attribute) {
            if ($closure($attribute))
                return $attribute;
        }

        return null;
    }
}
