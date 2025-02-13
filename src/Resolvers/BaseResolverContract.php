<?php

namespace SerterSerbest\LTO\Resolvers;

use ReflectionParameter;
use SerterSerbest\LTO\Contracts\PropsResolverContract;
use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\ResolvedProperty;
use SerterSerbest\LTO\ResolvedPropertyCollection;

abstract class BaseResolverContract implements PropsResolverContract
{
    abstract protected function getPropertyValue(Reflect $reflect, string $name): mixed;

    /**
     * @param Reflect $reflect
     * @return ResolvedPropertyCollection<ResolvedProperty>
     */
    public function resolve(Reflect $reflect): ResolvedPropertyCollection
    {
        $propsCollection = new ResolvedPropertyCollection();
        $constructor = $reflect->getClass()->getConstructor();

        if ($constructor->getNumberOfParameters() === 0)
            return $propsCollection;

        foreach ($constructor->getParameters() as $reflectionParameter) {
            $paramName = $reflectionParameter->getName();
            $reflectionProperty = $reflect->getProperty($paramName);
            $propertyValue = $this->getPropertyValue($reflect, $reflectionParameter->getName());

            $resolvedProperty = $this->getResolvedProperty($reflectionParameter, $reflectionProperty, $propertyValue);

            $propsCollection->push($resolvedProperty);
        }

        return $propsCollection;
    }

    protected function getResolvedProperty(ReflectionParameter $parameter, \ReflectionProperty $property, $value): ResolvedProperty
    {
        return ResolvedProperty::make(
            $parameter->getName(),
            $value,
            $parameter->getType()?->getName(),
            $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
        );
    }
}
