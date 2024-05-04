<?php
namespace Api\V1\Rest\Parameters;

class ParametersResourceFactory
{
    public function __invoke($services)
    {
        return new ParametersResource($services);
    }
}
