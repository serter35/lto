<?php

namespace SerterSerbest\LTO\Resolvers\Object;

use SerterSerbest\LTO\Contracts\PropsResolverContract;
use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\Resolvers\BaseResolverContract;

class ObjectResolverContract extends BaseResolverContract implements PropsResolverContract
{
    public function __construct(private readonly object $object)
    {
    }

    protected function getPropertyValue(Reflect $reflect, string $name): mixed
    {
        return $this->object->$name ?? null;
    }
}
