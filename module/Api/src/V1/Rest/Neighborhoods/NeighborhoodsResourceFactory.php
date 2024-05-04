<?php
namespace Api\V1\Rest\Neighborhoods;

class NeighborhoodsResourceFactory
{
    public function __invoke($services)
    {
        return new NeighborhoodsResource($services);
    }
}
