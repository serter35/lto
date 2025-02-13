<?php

namespace SerterSerbest\LTO\Resolvers\Array;

use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\Resolvers\BaseResolverContract;

class ArrayResolver extends BaseResolverContract
{
    public function __construct(private readonly array $data)
    {
    }

    protected function getPropertyValue(Reflect $reflect, string $name): mixed
    {
        return $this->data[$name] ?? null;
    }
}
