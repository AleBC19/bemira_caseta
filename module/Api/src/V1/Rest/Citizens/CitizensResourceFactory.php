<?php
namespace Api\V1\Rest\Citizens;

class CitizensResourceFactory
{
    public function __invoke($services)
    {
        return new CitizensResource($services);
    }
}
