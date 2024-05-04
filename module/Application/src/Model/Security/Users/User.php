<?php
namespace Application\Model\Security\Users;

use Application\Library\Model\Entity;
use Application\Model\Catalog\Citizens\CitizenTrait;

class User extends Entity
{
    use UserTrait;
    use CitizenTrait;
}