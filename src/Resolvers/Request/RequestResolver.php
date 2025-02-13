<?php

namespace SerterSerbest\LTO\Resolvers\Request;

use Illuminate\Http\Request;
use SerterSerbest\LTO\Attributes\Request\FromBody;
use SerterSerbest\LTO\Attributes\Request\FromQuery;
use SerterSerbest\LTO\Attributes\Request\FromRequestContract;
use SerterSerbest\LTO\Attributes\Request\FromRoute;
use SerterSerbest\LTO\Contracts\RequestParameterValueContract;
use SerterSerbest\LTO\Helpers\Reflect;
use SerterSerbest\LTO\Resolvers\BaseResolverContract;

class RequestResolver extends BaseResolverContract
{
    private RequestParameterValueResolver $parameterValueResolver;

    public function __construct(
        private readonly Request $request,
    )
    {
        $this->parameterValueResolver = new RequestParameterValueResolver($this->request);
    }

    protected function getPropertyValue(Reflect $reflect, string $name): mixed
    {
        $requestParameterValue = $this->getRequestParameterValue($reflect, $name);

        return $this->parameterValueResolver->getValue($requestParameterValue, $name);
    }

    private function getRequestParameterValue(Reflect $reflect, $propertyName): RequestParameterValueContract
    {
        $attribute = $reflect->findPropertyAttribute($propertyName, $this->searchAttributes(...));
        $attribute ??= $reflect->findAttribute($this->searchAttributes(...));

        return $attribute?->newInstance() ?? new RequestParameterDefaultValueContract;
    }

    private function searchAttributes(\ReflectionAttribute $attribute): bool
    {
        return in_array(
            $attribute->getName(),
            [FromQuery::class, FromBody::class, FromRoute::class, FromRequestContract::class]
        );
    }
}
