<?php

namespace SerterSerbest\LTO\Contracts;

use Illuminate\Http\Request;

interface RequestParameterValueContract
{
    public function getInput(Request $request, string $parameterName): mixed;
}
