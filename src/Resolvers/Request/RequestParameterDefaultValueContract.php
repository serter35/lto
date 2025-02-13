<?php

namespace SerterSerbest\LTO\Resolvers\Request;

use Illuminate\Http\Request;
use SerterSerbest\LTO\Contracts\RequestParameterValueContract;

class RequestParameterDefaultValueContract implements RequestParameterValueContract
{
    public function getInput(Request $request, string $parameterName): mixed
    {
        return $request->input($parameterName);
    }
}
