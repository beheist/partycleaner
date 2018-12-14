<?php
namespace Beheist\PartyCleaner\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use Neos\Party\Domain\Model\ElectronicAddress;

/**
 * @Flow\Scope("singleton")
 *
 * The Neos.Party package doesn't bring a repo for these, so we create one.
 */
class ElectronicAddressRepository extends Repository
{
    const ENTITY_CLASSNAME = ElectronicAddress::class;
}
