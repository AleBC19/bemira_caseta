<?php
namespace Api\V1\Rest\States;

class StatesResourceFactory
{
    public function __invoke($services)
    {
        return new StatesResource($services);
    }
}
