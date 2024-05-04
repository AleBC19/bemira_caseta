<?php
namespace Application\Model\Catalogo\TipoVehiculos;

use Application\Library\Model\Entity;
use Application\Model\Security\Menus\MenuTrait;

class ProfilePrivilege extends Entity
{
    use TipoVehiculosTrait;
    use MenuTrait;
}