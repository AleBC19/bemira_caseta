<?php
namespace Api\V1\Rest\Profiles;

class ProfilesResourceFactory
{
    public function __invoke($services)
    {
        return new ProfilesResource($services);
    }
}
