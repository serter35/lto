<?php

namespace SerterSerbest\LTO\Contracts;

use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\ResolvedPropertyCollection;

interface PropsResolverContract
{
    public function resolve(Reflect $reflect): ResolvedPropertyCollection;
}
