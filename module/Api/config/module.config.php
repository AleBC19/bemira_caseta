<?php
return [
    'service_manager' => [
        'factories' => [
            \Api\V1\Rest\Authentication\AuthenticationResource::class => \Api\V1\Rest\Authentication\AuthenticationResourceFactory::class,
            \Api\V1\Rest\Refresh\RefreshResource::class => \Api\V1\Rest\Refresh\RefreshResourceFactory::class,
            \Api\V1\Rest\Users\UsersResource::class => \Api\V1\Rest\Users\UsersResourceFactory::class,
            \Api\V1\Rest\UsersPrivileges\UsersPrivilegesResource::class => \Api\V1\Rest\UsersPrivileges\UsersPrivilegesResourceFactory::class,
            \Api\V1\Rest\Profiles\ProfilesResource::class => \Api\V1\Rest\Profiles\ProfilesResourceFactory::class,
            \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesResource::class => \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesResourceFactory::class,
            \Api\V1\Rest\Citizens\CitizensResource::class => \Api\V1\Rest\Citizens\CitizensResourceFactory::class,
            \Api\V1\Rest\Menus\MenusResource::class => \Api\V1\Rest\Menus\MenusResourceFactory::class,
            \Api\V1\Rest\States\StatesResource::class => \Api\V1\Rest\States\StatesResourceFactory::class,
            \Api\V1\Rest\Municipalities\MunicipalitiesResource::class => \Api\V1\Rest\Municipalities\MunicipalitiesResourceFactory::class,
            \Api\V1\Rest\Neighborhoods\NeighborhoodsResource::class => \Api\V1\Rest\Neighborhoods\NeighborhoodsResourceFactory::class,
            \Api\V1\Rest\Storage\StorageResource::class => \Api\V1\Rest\Storage\StorageResourceFactory::class,
            \Api\V1\Rest\Parameters\ParametersResource::class => \Api\V1\Rest\Parameters\ParametersResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api.rest.authentication' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/authentication[/:authentication_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Authentication\\Controller',
                    ],
                ],
            ],
            'api.rest.refresh' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/refresh[/:refresh_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Refresh\\Controller',
                    ],
                ],
            ],
            'api.rest.users' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/users[/:users_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Users\\Controller',
                    ],
                ],
            ],
            'api.rest.users-privileges' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/users-privileges[/:users_privileges_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\UsersPrivileges\\Controller',
                    ],
                ],
            ],
            'api.rest.profiles' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/profiles[/:profiles_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Profiles\\Controller',
                    ],
                ],
            ],
            'api.rest.profiles-privileges' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/profiles-privileges[/:profiles_privileges_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\ProfilesPrivileges\\Controller',
                    ],
                ],
            ],
            'api.rest.citizens' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/citizens[/:citizens_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Citizens\\Controller',
                    ],
                ],
            ],
            'api.rest.menus' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/menus[/:menus_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Menus\\Controller',
                    ],
                ],
            ],
            'api.rest.states' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/states[/:states_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\States\\Controller',
                    ],
                ],
            ],
            'api.rest.municipalities' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/municipalities[/:municipalities_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Municipalities\\Controller',
                    ],
                ],
            ],
            'api.rest.neighborhoods' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/neighborhoods[/:neighborhoods_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Neighborhoods\\Controller',
                    ],
                ],
            ],
            'api.rest.storage' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/storage[/:storage_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Storage\\Controller',
                    ],
                ],
            ],
            'api.rest.parameters' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/parameters[/:parameters_id]',
                    'defaults' => [
                        'controller' => 'Api\\V1\\Rest\\Parameters\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'api.rest.authentication',
            1 => 'api.rest.refresh',
            2 => 'api.rest.users',
            3 => 'api.rest.users-privileges',
            4 => 'api.rest.profiles',
            5 => 'api.rest.profiles-privileges',
            6 => 'api.rest.citizens',
            7 => 'api.rest.menus',
            11 => 'api.rest.states',
            12 => 'api.rest.municipalities',
            15 => 'api.rest.neighborhoods',
            17 => 'api.rest.storage',
            24 => 'api.rest.parameters',
        ],
    ],
    'api-tools-rest' => [
        'Api\\V1\\Rest\\Authentication\\Controller' => [
            'listener' => \Api\V1\Rest\Authentication\AuthenticationResource::class,
            'route_name' => 'api.rest.authentication',
            'route_identifier_name' => 'authentication_id',
            'collection_name' => 'authentication',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Authentication\AuthenticationEntity::class,
            'collection_class' => \Api\V1\Rest\Authentication\AuthenticationCollection::class,
            'service_name' => 'authentication',
        ],
        'Api\\V1\\Rest\\Refresh\\Controller' => [
            'listener' => \Api\V1\Rest\Refresh\RefreshResource::class,
            'route_name' => 'api.rest.refresh',
            'route_identifier_name' => 'refresh_id',
            'collection_name' => 'refresh',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Refresh\RefreshEntity::class,
            'collection_class' => \Api\V1\Rest\Refresh\RefreshCollection::class,
            'service_name' => 'refresh',
        ],
        'Api\\V1\\Rest\\Users\\Controller' => [
            'listener' => \Api\V1\Rest\Users\UsersResource::class,
            'route_name' => 'api.rest.users',
            'route_identifier_name' => 'users_id',
            'collection_name' => 'users',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
                3 => 'PATCH',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Users\UsersEntity::class,
            'collection_class' => \Api\V1\Rest\Users\UsersCollection::class,
            'service_name' => 'users',
        ],
        'Api\\V1\\Rest\\UsersPrivileges\\Controller' => [
            'listener' => \Api\V1\Rest\UsersPrivileges\UsersPrivilegesResource::class,
            'route_name' => 'api.rest.users-privileges',
            'route_identifier_name' => 'users_privileges_id',
            'collection_name' => 'users_privileges',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\UsersPrivileges\UsersPrivilegesEntity::class,
            'collection_class' => \Api\V1\Rest\UsersPrivileges\UsersPrivilegesCollection::class,
            'service_name' => 'usersPrivileges',
        ],
        'Api\\V1\\Rest\\Profiles\\Controller' => [
            'listener' => \Api\V1\Rest\Profiles\ProfilesResource::class,
            'route_name' => 'api.rest.profiles',
            'route_identifier_name' => 'profiles_id',
            'collection_name' => 'profiles',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Profiles\ProfilesEntity::class,
            'collection_class' => \Api\V1\Rest\Profiles\ProfilesCollection::class,
            'service_name' => 'profiles',
        ],
        'Api\\V1\\Rest\\ProfilesPrivileges\\Controller' => [
            'listener' => \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesResource::class,
            'route_name' => 'api.rest.profiles-privileges',
            'route_identifier_name' => 'profiles_privileges_id',
            'collection_name' => 'profiles_privileges',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesEntity::class,
            'collection_class' => \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesCollection::class,
            'service_name' => 'profilesPrivileges',
        ],
        'Api\\V1\\Rest\\Citizens\\Controller' => [
            'listener' => \Api\V1\Rest\Citizens\CitizensResource::class,
            'route_name' => 'api.rest.citizens',
            'route_identifier_name' => 'citizens_id',
            'collection_name' => 'citizens',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Citizens\CitizensEntity::class,
            'collection_class' => \Api\V1\Rest\Citizens\CitizensCollection::class,
            'service_name' => 'citizens',
        ],
        'Api\\V1\\Rest\\Menus\\Controller' => [
            'listener' => \Api\V1\Rest\Menus\MenusResource::class,
            'route_name' => 'api.rest.menus',
            'route_identifier_name' => 'menus_id',
            'collection_name' => 'menus',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Menus\MenusEntity::class,
            'collection_class' => \Api\V1\Rest\Menus\MenusCollection::class,
            'service_name' => 'menus',
        ],
        'Api\\V1\\Rest\\States\\Controller' => [
            'listener' => \Api\V1\Rest\States\StatesResource::class,
            'route_name' => 'api.rest.states',
            'route_identifier_name' => 'states_id',
            'collection_name' => 'states',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\States\StatesEntity::class,
            'collection_class' => \Api\V1\Rest\States\StatesCollection::class,
            'service_name' => 'states',
        ],
        'Api\\V1\\Rest\\Municipalities\\Controller' => [
            'listener' => \Api\V1\Rest\Municipalities\MunicipalitiesResource::class,
            'route_name' => 'api.rest.municipalities',
            'route_identifier_name' => 'municipalities_id',
            'collection_name' => 'municipalities',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Municipalities\MunicipalitiesEntity::class,
            'collection_class' => \Api\V1\Rest\Municipalities\MunicipalitiesCollection::class,
            'service_name' => 'municipalities',
        ],
        'Api\\V1\\Rest\\Neighborhoods\\Controller' => [
            'listener' => \Api\V1\Rest\Neighborhoods\NeighborhoodsResource::class,
            'route_name' => 'api.rest.neighborhoods',
            'route_identifier_name' => 'neighborhoods_id',
            'collection_name' => 'neighborhoods',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Neighborhoods\NeighborhoodsEntity::class,
            'collection_class' => \Api\V1\Rest\Neighborhoods\NeighborhoodsCollection::class,
            'service_name' => 'neighborhoods',
        ],
        'Api\\V1\\Rest\\Storage\\Controller' => [
            'listener' => \Api\V1\Rest\Storage\StorageResource::class,
            'route_name' => 'api.rest.storage',
            'route_identifier_name' => 'storage_id',
            'collection_name' => 'storage',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'offset',
                4 => 'limit',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Storage\StorageEntity::class,
            'collection_class' => \Api\V1\Rest\Storage\StorageCollection::class,
            'service_name' => 'storage',
        ],
        'Api\\V1\\Rest\\Parameters\\Controller' => [
            'listener' => \Api\V1\Rest\Parameters\ParametersResource::class,
            'route_name' => 'api.rest.parameters',
            'route_identifier_name' => 'parameters_id',
            'collection_name' => 'parameters',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [
                0 => 'select',
                1 => 'where',
                2 => 'order',
                3 => 'limit',
                4 => 'offset',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Api\V1\Rest\Parameters\ParametersEntity::class,
            'collection_class' => \Api\V1\Rest\Parameters\ParametersCollection::class,
            'service_name' => 'parameters',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'Api\\V1\\Rest\\Authentication\\Controller' => 'Json',
            'Api\\V1\\Rest\\Refresh\\Controller' => 'Json',
            'Api\\V1\\Rest\\Users\\Controller' => 'Json',
            'Api\\V1\\Rest\\UsersPrivileges\\Controller' => 'Json',
            'Api\\V1\\Rest\\Profiles\\Controller' => 'Json',
            'Api\\V1\\Rest\\ProfilesPrivileges\\Controller' => 'Json',
            'Api\\V1\\Rest\\Citizens\\Controller' => 'Json',
            'Api\\V1\\Rest\\Menus\\Controller' => 'Json',
            'Api\\V1\\Rest\\States\\Controller' => 'Json',
            'Api\\V1\\Rest\\Municipalities\\Controller' => 'Json',
            'Api\\V1\\Rest\\Neighborhoods\\Controller' => 'Json',
            'Api\\V1\\Rest\\Storage\\Controller' => 'Json',
            'Api\\V1\\Rest\\Parameters\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Api\\V1\\Rest\\Authentication\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Refresh\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Users\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\UsersPrivileges\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Profiles\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\ProfilesPrivileges\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Citizens\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Menus\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\States\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Municipalities\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Neighborhoods\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Storage\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Parameters\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Api\\V1\\Rest\\Authentication\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Refresh\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Users\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\UsersPrivileges\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Profiles\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\ProfilesPrivileges\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Citizens\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Menus\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\States\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Municipalities\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Neighborhoods\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Storage\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
            'Api\\V1\\Rest\\Parameters\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \Api\V1\Rest\Authentication\AuthenticationEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.authentication',
                'route_identifier_name' => 'authentication_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Authentication\AuthenticationCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.authentication',
                'route_identifier_name' => 'authentication_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Refresh\RefreshEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.refresh',
                'route_identifier_name' => 'refresh_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Refresh\RefreshCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.refresh',
                'route_identifier_name' => 'refresh_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Users\UsersEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.users',
                'route_identifier_name' => 'users_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Users\UsersCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.users',
                'route_identifier_name' => 'users_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\UsersPrivileges\UsersPrivilegesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.users-privileges',
                'route_identifier_name' => 'users_privileges_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\UsersPrivileges\UsersPrivilegesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.users-privileges',
                'route_identifier_name' => 'users_privileges_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Profiles\ProfilesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.profiles',
                'route_identifier_name' => 'profiles_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Profiles\ProfilesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.profiles',
                'route_identifier_name' => 'profiles_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.profiles-privileges',
                'route_identifier_name' => 'profiles_privileges_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\ProfilesPrivileges\ProfilesPrivilegesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.profiles-privileges',
                'route_identifier_name' => 'profiles_privileges_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Citizens\CitizensEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.citizens',
                'route_identifier_name' => 'citizens_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Citizens\CitizensCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.citizens',
                'route_identifier_name' => 'citizens_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Menus\MenusEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.menus',
                'route_identifier_name' => 'menus_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Menus\MenusCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.menus',
                'route_identifier_name' => 'menus_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\States\StatesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.states',
                'route_identifier_name' => 'states_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\States\StatesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.states',
                'route_identifier_name' => 'states_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Municipalities\MunicipalitiesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.municipalities',
                'route_identifier_name' => 'municipalities_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Municipalities\MunicipalitiesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.municipalities',
                'route_identifier_name' => 'municipalities_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Neighborhoods\NeighborhoodsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.neighborhoods',
                'route_identifier_name' => 'neighborhoods_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Neighborhoods\NeighborhoodsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.neighborhoods',
                'route_identifier_name' => 'neighborhoods_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Storage\StorageEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.storage',
                'route_identifier_name' => 'storage_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Storage\StorageCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.storage',
                'route_identifier_name' => 'storage_id',
                'is_collection' => true,
            ],
            \Api\V1\Rest\Parameters\ParametersEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.parameters',
                'route_identifier_name' => 'parameters_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Api\V1\Rest\Parameters\ParametersCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.parameters',
                'route_identifier_name' => 'parameters_id',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'Api\\V1\\Rest\\Refresh\\Controller' => [
            'input_filter' => 'Api\\V1\\Rest\\Refresh\\Validator',
        ],
        'Api\\V1\\Rest\\Authentication\\Controller' => [
            'input_filter' => 'Api\\V1\\Rest\\Authentication\\Validator',
        ],
        'Api\\V1\\Rest\\Users\\Controller' => [
            'input_filter' => 'Api\\V1\\Rest\\Users\\Validator',
        ],
        'Api\\V1\\Rest\\Profiles\\Controller' => [
            'input_filter' => 'Api\\V1\\Rest\\Profiles\\Validator',
        ],
        'Api\\V1\\Rest\\Citizens\\Controller' => [
            'input_filter' => 'Api\\V1\\Rest\\Citizens\\Validator',
        ],
        'Api\\V1\\Rest\\Storage\\Controller' => [
            'input_filter' => 'Api\\V1\\Rest\\Storage\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'Api\\V1\\Rest\\Refresh\\Validator' => [],
        'Api\\V1\\Rest\\Authentication\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '255',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmUserAuthentication',
                'description' => 'Username',
                'field_type' => 'string',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '50',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmPasswordAuthentication',
                'description' => 'User\'s password',
                'field_type' => 'string',
            ],
        ],
        'Api\\V1\\Rest\\Users\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmCitizenIdUser',
                'description' => 'Citizen ID',
                'field_type' => 'int',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmProfileIdUser',
                'description' => 'Profile ID',
                'field_type' => 'int',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '255',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmUserNameUser',
                'description' => 'User name',
                'field_type' => 'string',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmPasswordUser',
                'description' => 'User password',
                'field_type' => 'string',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmPrivilegesUser',
                'description' => 'Set of privileges ID for the user',
                'field_type' => 'array',
            ],
        ],
        'Api\\V1\\Rest\\Profiles\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '30',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmNameProfile',
                'description' => 'Profile name',
                'field_type' => 'string',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmPrivilegesProfile',
                'description' => 'List of privileges',
                'field_type' => 'array',
            ],
        ],
        'Api\\V1\\Rest\\Committees\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmElectoralProcessIdCommittee',
                'description' => 'Electoral process ID of the committee',
                'field_type' => 'int',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmSectionIdCommittee',
                'description' => 'Section ID of the committee',
                'field_type' => 'int',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '10',
                            'min' => '10',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmRegistrationDateCommittee',
                'description' => 'Date in which the committee was registered',
                'field_type' => 'string',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '8',
                            'min' => '5',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'description' => 'Time in which the committee was registered',
                'name' => 'frmRegistrationTimeCommittee',
                'field_type' => 'string',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmStepCommittee',
                'description' => 'Step in which a user left wahile creating or editing a committee',
                'field_type' => 'int',
            ],
        ],
        'Api\\V1\\Rest\\CommitteeMembers\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmIdCommitteeMember',
                'description' => 'Committee member ID',
                'field_type' => 'int',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmJobPositionIdCommitteeMember',
                'description' => 'Job position of the member',
                'field_type' => 'int',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmElectoralProcessIdCommitteeMember',
                'description' => 'Electoral process ID',
                'field_type' => 'int',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmCommitteeIdCommitteeMember',
                'description' => 'Committee ID',
                'field_type' => 'int',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmCitizenIdCommitteeMember',
                'description' => 'Citizen ID',
                'field_type' => 'int',
            ],
        ],
        'Api\\V1\\Rest\\Citizens\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => '18',
                            'max' => '18',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmVoterIdCitizen',
                'description' => 'Voter ID',
                'field_type' => 'int',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '30',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmNameCitizen',
                'description' => 'Citizen\'s name',
                'field_type' => 'string',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '30',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmLastNameCitizen',
                'description' => 'Last name',
                'field_type' => 'string',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmMaternalSurnameCitizen',
                'description' => 'Maternal surname',
                'field_type' => 'string',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '10',
                            'min' => '10',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmPhoneCitizen',
                'description' => 'Citizen\'s phone',
                'field_type' => 'string',
            ],
            5 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '10',
                            'min' => '10',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmCellPhoneCitizen',
                'description' => 'Citizen\'s cell phone',
                'field_type' => 'string',
            ],
            6 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '255',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmEmailAddressCitizen',
                'description' => 'Citizen\'s email address',
                'field_type' => 'string',
            ],
            7 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmNeighborhoodIdCitizen',
                'description' => 'ID of the neighborhood',
                'field_type' => 'int',
            ],
            8 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '40',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmStreetCitizen',
                'description' => 'Citizen\'s street',
                'field_type' => 'string',
            ],
            9 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '15',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmOutdoorNumberCitizen',
                'description' => 'Outdoor number',
                'field_type' => 'string',
            ],
            10 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '15',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmInteriorNumberCitizen',
                'description' => 'Interior number',
                'field_type' => 'string',
            ],
            11 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '10',
                            'min' => '10',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmDateOfBirthCitizen',
                'description' => 'Date of birth',
                'field_type' => 'string',
            ],
            12 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmJobPositionIdCitizen',
                'description' => 'Job position ID',
                'field_type' => 'int',
            ],
            13 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmSignatureCitizen',
                'description' => 'Name of the file to save the signature image',
                'field_type' => 'string',
            ],
            14 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmFrontIneCitizen',
                'description' => 'Name of the file to save the image of front INE',
                'field_type' => 'string',
            ],
            15 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmBackIneCitizen',
                'description' => 'Name of the file to save the image of back INE',
                'field_type' => 'string',
            ],
            16 => [
                'required' => false,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'description' => 'Image of signature in base64 format',
                'name' => 'frmSignatureBase64Citizen',
                'field_type' => 'string',
            ],
            17 => [
                'required' => false,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'description' => 'Image of front INE in base64 format',
                'name' => 'frmFrontIneBase64Citizen',
                'field_type' => 'string',
            ],
            18 => [
                'required' => false,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'description' => 'Image of signature in base64 format',
                'name' => 'frmBackIneBase64Citizen',
                'field_type' => 'string',
            ],
            19 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '10',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmSectionNameCitizen',
                'description' => 'Section Name of Citizen',
                'field_type' => 'string',
            ],
        ],
        'Api\\V1\\Rest\\Storage\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '45',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmAppKeyDropbox',
                'description' => 'App key from dropbox account',
                'field_type' => 'string',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '45',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmAppSecretDropbox',
                'description' => 'App secret from dropbox account',
                'field_type' => 'string',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmRootPathDropbox',
                'description' => 'Root path for all apps files',
                'field_type' => 'string',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmRefreshTokenDropbox',
                'description' => 'Refresh token from dropbox',
                'field_type' => 'string',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '500',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmAccessTokenDropbox',
                'description' => 'Access token from dropbox',
                'field_type' => 'string',
            ],
            5 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'max' => '100',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmAccessCodeDropbox',
                'description' => 'Access code from dropbox',
                'field_type' => 'string',
            ],
        ],
        'Api\\V1\\Rest\\UsersSections\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmSectionalAssignmentIdUserSection',
                'description' => 'Sectional assignment for section privileges',
                'field_type' => 'int',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'frmStateIdUserSection',
                'description' => 'State assigned for section privileges',
                'field_type' => 'int',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'description' => 'List of local districts assigned to the user',
                'name' => 'frmLocalDistrictsUserSection',
                'field_type' => 'array',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmFederalDistrictsUserSection',
                'description' => 'List of federal districts assigned to the user',
                'field_type' => 'array',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmMunicipalitiesUserSection',
                'description' => 'List of municipalities assigned to the user',
                'field_type' => 'array',
            ],
            5 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmSectionsUserSection',
                'description' => 'List of sections assigned to the user',
                'field_type' => 'array',
            ],
        ],
        'Api\\V1\\Rest\\CommitteeMembersReport\\Validator' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '1',
                            'inclusive' => true,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'frmIdReportType',
                'description' => 'ID of a report type: general or user',
                'field_type' => 'int',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '1',
                            'inclusive' => true,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'frmIdElectoralProcess',
                'description' => 'ID of the electoral process to consult',
                'field_type' => 'int',
            ],
            2 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '1',
                            'inclusive' => true,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'frmIdState',
                'description' => 'ID of the state to consult',
                'field_type' => 'int',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '1',
                            'inclusive' => true,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'frmIdUser',
                'description' => 'ID of the user',
                'field_type' => 'int',
            ],
            4 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Digits::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '1',
                            'inclusive' => true,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'frmIdSectionalAssignment',
                'description' => 'ID of the sectional assignment',
                'field_type' => 'int',
            ],
            5 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmLocalDistricts',
                'description' => 'Set of local districts to consult',
                'field_type' => 'array',
            ],
            6 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\IsCountable::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'frmFederalDistricts',
                'description' => 'Federal districts to consult',
                'field_type' => 'array',
            ],
            7 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'frmNameReportType',
                'description' => 'Report type name',
                'field_type' => 'string',
            ],
            8 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'frmNameElectoralProcess',
                'description' => 'Electoral process name',
                'field_type' => 'string',
            ],
            9 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'frmNameState',
                'description' => 'State name',
                'field_type' => 'string',
            ],
            10 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'frmNameUser',
                'description' => 'User name',
                'field_type' => 'string',
            ],
            11 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'frmNameSectionalAssignment',
                'description' => 'Sectional assignment name',
                'field_type' => 'string',
            ],
        ],
    ],
];
