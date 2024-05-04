<?php
namespace Application\Model\PostalAddress\Neighborhoods;

use Application\Library\Model\Entity;
use Application\Model\PostalAddress\ZipCodes\ZipCodeTrait;
use Application\Model\PostalAddress\States\StateTrait;
use Application\Model\PostalAddress\Municipalities\MunicipalityTrait;
use Application\Model\PostalAddress\Cities\CityTrait;

class Neighborhood extends Entity
{
    use NeighborhoodTrait;
    use ZipCodeTrait;
    use StateTrait;
    use MunicipalityTrait;
    use CityTrait;
}