<?php
namespace Beheist\PartyCleaner\Command;

use Beheist\PartyCleaner\Domain\Repository\ElectronicAddressRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Party\Domain\Model\AbstractParty;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Model\Person;
use Neos\Party\Domain\Repository\PartyRepository;

/**
 * @Flow\Scope("singleton")
 */
class PartyCleanerCommandController extends CommandController
{
    /**
     * @var ElectronicAddressRepository
     * @Flow\Inject
     */
    protected $electronicAddressRepository;

    /**
     * @var PartyRepository
     * @Flow\Inject
     */
    protected $partyRepository;

    /**
     * Deletes orphan ElectronicAddress instances.
     *
     * When deleting a user in the Neos backend, ElectronicAddress instances are not cleaned up automatically, as
     * there is an n:m relationship between Party and ElectronicAddress. For many use cases, such as the one we
     * use in Sandstorm/UserManagement, we assume a 1:1 relationship between Party and ElectronicAddress.
     * This command cleans up all "orphan" ElectronicAddress instances that are in the DB and have no relatiobship
     * to any user.
     */
    public function removeOrphanAddressesCommand()
    {
        $existingAddresses = $this->electronicAddressRepository->findAll()->toArray();

        /** @var AbstractParty $party */
        foreach ($this->partyRepository->findAll() as $party) {
            // if some package used the party package to create custom parties, we don't touch them
            if (!$party instanceof Person) {
                continue;
            }

            /** @var ElectronicAddress $address */
            foreach ($party->getElectronicAddresses() as $address) {
                if (($key = array_search($address, $existingAddresses)) !== false) {
                    unset($existingAddresses[$key]);
                }
            }
        }

        // Clean all addresses that have not been found now
        /** @var ElectronicAddress $address */
        $countRemoved = 0;
        foreach ($existingAddresses as $address) {
            $countRemoved++;
            $this->outputLine("Removing: " . $address->getIdentifier());
            $this->electronicAddressRepository->remove($address);
        }
        $this->outputLine("Successfully removed $countRemoved orphan addresses.");
    }
}
