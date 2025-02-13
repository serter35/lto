<?php

namespace SerterSerbest\LTO\Resolvers\EloquentModel;

use Illuminate\Database\Eloquent\Model;
use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\Resolvers\BaseResolverContract;

class ModelResolver extends BaseResolverContract
{
    public function __construct(private readonly Model $model)
    {
    }

    protected function getPropertyValue(Reflect $reflect, string $name): mixed
    {
        $attributes = $this->model->toArray();

        return $attributes[$name] ?? null;
    }
}
