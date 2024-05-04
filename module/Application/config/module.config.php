<?php

declare(strict_types=1);

namespace Application;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\Adapter;
use Application\Model\Catalog\Citizens\CitizensTable;
use Application\Model\Catalog\Citizens\Citizen;
use Application\Model\Security\Logs\LogsTable;
use Application\Model\Security\Logs\Log;
use Application\Model\Security\Menus\MenusTable;
use Application\Model\Security\Menus\Menu;
use Application\Model\PostalAddress\Municipalities\MunicipalitiesTable;
use Application\Model\PostalAddress\Municipalities\Municipality;
use Application\Model\Security\Profiles\ProfilesTable;
use Application\Model\Security\Profiles\Profile;
use Application\Model\Security\ProfilesPrivileges\ProfilesPrivilegesTable;
use Application\Model\Security\ProfilesPrivileges\ProfilePrivilege;
use Application\Model\PostalAddress\States\StatesTable;
use Application\Model\PostalAddress\States\State;
use Application\Model\Security\Users\UsersTable;
use Application\Model\Security\Users\User;
use Application\Model\Security\UsersPrivileges\UsersPrivilegesTable;
use Application\Model\Security\UsersPrivileges\UserPrivilege;
use Application\Model\PostalAddress\Neighborhoods\NeighborhoodsTable;
use Application\Model\PostalAddress\Neighborhoods\Neighborhood;
use Application\Model\PostalAddress\ZipCodes\ZipCodesTable;
use Application\Model\PostalAddress\ZipCodes\ZipCode;
use Application\Model\Catalog\JobPositions\JobPositionsTable;
use Application\Model\Catalog\JobPositions\JobPosition;
use Application\Model\PostalAddress\Cities\CitiesTable;
use Application\Model\PostalAddress\Cities\City;
use Application\Model\Config\Dropbox\DropboxTable;
use Application\Model\Config\Dropbox\Dropbox;
use Application\Library\Storage\Storage;
use Application\Model\Config\Parameters\ParametersTable;
use Application\Model\Config\Parameters\Parameter;

return [
    'router'       => [
        'routes' => [
            'home' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers'  => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            Storage::class => function($sm) {
                return new Storage($sm);
            },
            CitiesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new City());
                $tableIdentifier = new TableIdentifier('cities', 'postal_address');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new CitiesTable($tableGateway, $sm);
            },
            CitizensTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Citizen());
                $tableIdentifier = new TableIdentifier('citizens', 'catalog');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new CitizensTable($tableGateway, $sm);
            },
            DropboxTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Dropbox());
                $tableIdentifier = new TableIdentifier('dropbox', 'config');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new DropboxTable($tableGateway, $sm);
            },
            JobPositionsTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new JobPosition());
                $tableIdentifier = new TableIdentifier('job_positions', 'catalog');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new JobPositionsTable($tableGateway, $sm);
            },
            LogsTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Log());
                $tableIdentifier = new TableIdentifier('logs', 'security');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new LogsTable($tableGateway, $sm);
            },
            MenusTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Menu());
                $tableIdentifier = new TableIdentifier('menus', 'security');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new MenusTable($tableGateway, $sm);
            },
            MunicipalitiesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Municipality());
                $tableIdentifier = new TableIdentifier('municipalities', 'postal_address');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new MunicipalitiesTable($tableGateway, $sm);
            },
            NeighborhoodsTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Neighborhood());
                $tableIdentifier = new TableIdentifier('neighborhoods', 'postal_address');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new NeighborhoodsTable($tableGateway, $sm);
            },
            ParametersTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Parameter());
                $tableIdentifier = new TableIdentifier('parameters', 'config');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new ParametersTable($tableGateway, $sm);
            },
            ProfilesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new Profile());
                $tableIdentifier = new TableIdentifier('profiles', 'security');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new ProfilesTable($tableGateway, $sm);
            },
            ProfilesPrivilegesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new ProfilePrivilege());
                $tableIdentifier = new TableIdentifier('profiles_privileges', 'security');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new ProfilesPrivilegesTable($tableGateway, $sm);
            },
            StatesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new State());
                $tableIdentifier = new TableIdentifier('states', 'postal_address');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new StatesTable($tableGateway, $sm);
            },
            UsersTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new User());
                $tableIdentifier = new TableIdentifier('users', 'security');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new UsersTable($tableGateway, $sm);
            },
            UsersPrivilegesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new UserPrivilege());
                $tableIdentifier = new TableIdentifier('users_privileges', 'security');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new UsersPrivilegesTable($tableGateway, $sm);
            },
            ZipCodesTable::class => function($sm) {
                $resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new ZipCode());
                $tableIdentifier = new TableIdentifier('zip_codes', 'postal_address');
                $tableGateway = new TableGateway($tableIdentifier, $sm->get(Adapter::class), null, $resultSetPrototype);
                return new ZipCodesTable($tableGateway, $sm);
            }
        ]
    ]
];
