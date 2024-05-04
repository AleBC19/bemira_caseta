<?php
namespace Api\V1\Rest\Municipalities;

class MunicipalitiesResourceFactory
{
    public function __invoke($services)
    {
        return new MunicipalitiesResource($services);
    }
}
