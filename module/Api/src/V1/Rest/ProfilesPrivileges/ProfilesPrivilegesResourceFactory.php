<?php
namespace Api\V1\Rest\ProfilesPrivileges;

class ProfilesPrivilegesResourceFactory
{
    public function __invoke($services)
    {
        return new ProfilesPrivilegesResource($services);
    }
}
