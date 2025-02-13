<?php

namespace SerterSerbest\LTO\Concerns;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

trait Validatable
{
    protected array $validated;

    protected Factory $validator;

    abstract protected function getValidationRules(): array;

    abstract protected static function getContainer(): Container;

    abstract public function getResource(): array;

    public function validator(): Factory
    {
        return $this->validator ??= $this->getContainer()->make(Factory::class);
    }

    final public function validate(): array
    {
        return $this->validated = $this->validator()->validate(
            $this->getValidationData(),
            $this->getValidationRules(),
            $this->getValidationMessages(),
            $this->getValidationAttributes()
        );
    }

    public function toValidatedArray(): array
    {
        return $this->validated;
    }

    public function toValidatedCollection(): Collection
    {
        return static::getContainer()->makeWith(Collection::class, ['items' => $this->validated]);
    }

    public function toValidatedModel(Model $model): Model
    {
        return $model->fill($this->validated);
    }

    final public function makeValidation(): Validator
    {
        return $this->validator()->make(
            $this->getValidationData(),
            $this->getValidationRules(),
            $this->getValidationMessages(),
            $this->getValidationAttributes()
        );
    }

    protected function getValidationMessages(): array
    {
        return [];
    }

    protected function getValidationAttributes(): array
    {
        return [];
    }

    protected function getValidationData(): array
    {
        return $this->getResource();
    }
}
