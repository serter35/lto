<?php

namespace SerterSerbest\LTO;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SerterSerbest\LTO\Contracts\LTOContract;
use SerterSerbest\LTO\Contracts\PropsResolverContract;
use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\Resolvers\Array\ArrayResolver;
use SerterSerbest\LTO\Resolvers\EloquentModel\ModelResolver;
use SerterSerbest\LTO\Resolvers\Object\ObjectResolverContract;
use SerterSerbest\LTO\Resolvers\Request\RequestResolver;

abstract class BaseDTO implements LTOContract, Arrayable
{
    protected array $resource = [];

    protected Reflect $reflect;

    protected static function boot(string $resolverClass, array $resolverArgs = []): static
    {
        $reflect = static::newReflect(static::class);
        $propsCollection = static::getPropsCollectionByResolver($reflect, $resolverClass, $resolverArgs);
        $instance = $reflect->getClass()->newInstanceArgs($resource = $propsCollection->toAssocArray());

        $instance->setResource($resource);
        $instance->setReflect(static::newReflect($instance));

        return $instance;
    }

    protected static function getPropsCollectionByResolver(
        Reflect $reflect,
        string $resolverClass,
        array $resolverArgs = []): ResolvedPropertyCollection
    {
        $propsResolver = static::getPropsResolver($resolverClass, $resolverArgs);

        return $propsResolver->resolve($reflect);
    }

    public static function fromContainer(Container $container): void
    {
        $container->bind(static::class, fn() => static::boot(RequestResolver::class));
    }

    public static function fromRequest(Request $request): static
    {
        return static::boot(RequestResolver::class, compact('request'));
    }

    public static function fromArray(array $data): static
    {
        return static::boot(ArrayResolver::class, compact('data'));
    }

    public static function fromModel(Model $model): static
    {
        return static::boot(ModelResolver::class, compact('model'));
    }

    public static function fromObject(object $object): static
    {
        return static::boot(ObjectResolverContract::class, compact('object'));
    }

    final protected static function getContainer(): Container
    {
        return Container::getInstance();
    }

    public function setReflect(Reflect $reflect): void
    {
        $this->reflect = $reflect;
    }

    public function getReflect(): Reflect
    {
        return $this->reflect;
    }

    public function getResource(): array
    {
        if ($this->resource)
            return $this->resource;

        $this->reflect = static::newReflect($this);

        foreach ($this->reflect->getConstructorParams() as $parameter) {
            $this->resource[$parameter->getName()] = $this->reflect->getPropertyValue($parameter->getName());
        }

        return $this->resource;
    }

    public function setResource(array $resource): void
    {
        $this->resource = $resource;
    }

    public function toArray(): array
    {
        return $this->getResource();
    }

    public function toCollection(): Collection
    {
        return static::getContainer()->makeWith(Collection::class, ['items' => $this->getResource()]);
    }

    public function toModel(string $className): Model
    {
        return new $className($this->getResource());
    }

    protected static function newReflect(object|string $objectOrClass): Reflect
    {
        return new Reflect($objectOrClass);
    }

    protected static function getPropsResolver(string $resolverClass, array $parameters): PropsResolverContract
    {
        return static::getContainer()->makeWith($resolverClass, $parameters);
    }
}
