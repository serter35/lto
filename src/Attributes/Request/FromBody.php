<?php

namespace SerterSerbest\LTO\Attributes\Request;

use Attribute;
use Illuminate\Http\Request;
use SerterSerbest\LTO\Contracts\RequestParameterValueContract;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER|Attribute::TARGET_CLASS)]
readonly class FromBody implements RequestParameterValueContract
{
    public function __construct(public ?string $parameterName = null)
    {
    }

    public function getInput(Request $request, string $parameterName): mixed
    {
        return $request->post($this->parameterName ?? $parameterName);
    }
}
