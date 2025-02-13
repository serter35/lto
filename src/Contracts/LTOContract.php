<?php

namespace SerterSerbest\LTO\Contracts;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @mixin Collection
 */
interface LTOContract
{
    /**
     * Type-hinted HTTP Request parameters are automatically resolved and injected.
     * This is achieved through the container's before resolving hook.
     *
     * @param Container $container
     * @return void
     */
    public static function fromContainer(Container $container): void;

    /**
     * Make from HTTP Request
     *
     * @param Request $request
     * @return static
     */
    public static function fromRequest(Request $request): static;

    /**
     * Make from Simple Array
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static;

    /**
     * Make from Eloquent Model
     *
     * @param Model $model
     * @return static
     */
    public static function fromModel(Model $model): static;

    /**
     * DTO converted as an Array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * DTO converted as a Collection
     *
     * @return Collection
     */
    public function toCollection(): Collection;

    /**
     * DTO converted as an Eloquent Model
     *
     * @param class-string $className
     * @return Model
     */
    public function toModel(string $className): Model;

}
