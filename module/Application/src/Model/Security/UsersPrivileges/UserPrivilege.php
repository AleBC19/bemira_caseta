<?php
namespace Application\Model\Security\UsersPrivileges;

use Application\Library\Model\Entity;
use Application\Model\Security\Menus\MenuTrait;

class UserPrivilege extends Entity
{
    use UserPrivilegeTrait;
    use MenuTrait;
}