<?php
namespace Api\V1\Rest\Refresh;

class RefreshResourceFactory
{
    public function __invoke($services)
    {
        return new RefreshResource($services);
    }
}
