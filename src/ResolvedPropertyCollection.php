<?php

namespace SerterSerbest\LTO;

class ResolvedPropertyCollection
{
    /**
     * @param array<ResolvedProperty> $items
     */
    public function __construct(private array $items = [])
    {
    }


    public function push(ResolvedProperty $resolvedProperty): self
    {
        $this->items[] = $resolvedProperty;

        return $this;
    }

    public function has($key): bool
    {

        return array_key_exists($key, $this->toAssocArray());
    }

    public function get(string $key): ?ResolvedProperty
    {
        foreach ($this->items as $resolvedProperty) {
            if ($resolvedProperty->name === $key)
                return $resolvedProperty;
        }

        return null;
    }

    public function toAssocArray(): array
    {
        $items = [];

        foreach ($this->items as $resolvedProperty) {
            $items[$resolvedProperty->name] = $resolvedProperty->getValue();
        }

        return $items;
    }

    public function setValue($propName, $key, $value): self
    {
        $resolvedProperty = $this->get($propName);

        $resolvedProperty->$key = $value;

        return $this;
    }
}
