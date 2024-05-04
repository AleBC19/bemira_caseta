<?php
namespace Application\Model\Security\ProfilesPrivileges;

use Application\Library\Model\Entity;
use Application\Model\Security\Menus\MenuTrait;

class ProfilePrivilege extends Entity
{
    use ProfilePrivilegeTrait;
    use MenuTrait;
}