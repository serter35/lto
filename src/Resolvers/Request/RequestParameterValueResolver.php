<?php

namespace SerterSerbest\LTO\Resolvers\Request;

use Illuminate\Http\Request;
use SerterSerbest\LTO\Contracts\RequestParameterValueContract;

class RequestParameterValueResolver
{
    public function __construct(private readonly Request $request)
    {
    }

    public function getValue(RequestParameterValueContract $valueResolver, string $parameterName): mixed
    {
        return $valueResolver->getInput($this->request, $parameterName);
    }
}
