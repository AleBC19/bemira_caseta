<?php
namespace Application\Model\Catalog\Citizens;

use Application\Library\Model\Entity;
use Application\Model\Security\Users\UserTrait;
use Application\Model\PostalAddress\Neighborhoods\NeighborhoodTrait;
use Application\Model\PostalAddress\ZipCodes\ZipCodeTrait;

class Citizen extends Entity
{
    use CitizenTrait;
    use UserTrait;
    use NeighborhoodTrait;
    use ZipCodeTrait;
}