<?php

namespace SerterSerbest\LTO\Attributes\Request;

use Attribute;
use Illuminate\Http\Request;
use SerterSerbest\LTO\Contracts\RequestParameterValueContract;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER|Attribute::TARGET_CLASS)]
readonly class FromQuery implements RequestParameterValueContract
{
    public function __construct(public ?string $parameterName = null)
    {
    }

    public function getInput(Request $request, string $parameterName): mixed
    {
        return $request->query($this->parameterName ?? $parameterName);
    }
}
