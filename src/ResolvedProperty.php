<?php

namespace SerterSerbest\LTO;

use ReflectionProperty;

class ResolvedProperty
{
    public string $name;

    public mixed $value;

    public ?string $type = null;

    public mixed $default = null;


    public static function make(string $name, mixed $value, ?string $type = null, mixed $default = null): static
    {
        $self = new static();

        $self->name = $name;
        $self->value = $value;
        $self->type = $type;
        $self->default = $default;

        return $self;
    }

    public static function fromReflectionProperty(ReflectionProperty $reflectionProperty, object $object): static
    {
        return static::make(
            $reflectionProperty->getName(),
            $reflectionProperty->getValue($object),
            $reflectionProperty->getType()?->getName(),
            $reflectionProperty->getDefaultValue(),
        );
    }

    public function isDefault(): bool
    {
        return !isset($this->value) && isset($this->default);
    }

    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    public function isName(string $name): bool
    {
        return $this->name === $name;
    }

    public function getValue(): mixed
    {
        return $this->value ?? $this->default;
    }
}
