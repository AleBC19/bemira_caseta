<?php
namespace Api\V1\Rest\Authentication;

class AuthenticationResourceFactory
{
    public function __invoke($services)
    {
        return new AuthenticationResource($services);
    }
}
