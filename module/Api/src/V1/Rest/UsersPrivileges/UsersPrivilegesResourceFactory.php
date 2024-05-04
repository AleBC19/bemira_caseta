<?php
namespace Api\V1\Rest\UsersPrivileges;

class UsersPrivilegesResourceFactory
{
    public function __invoke($services)
    {
        return new UsersPrivilegesResource($services);
    }
}
