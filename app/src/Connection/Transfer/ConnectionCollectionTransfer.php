<?php

declare(strict_types=1);

namespace App\Connection\Transfer;

use App\Flight\Domain\Entity\Flight;
use App\Flight\Domain\Entity\FlightCollection;

final class ConnectionCollectionTransfer
{
    /**
     * @param \App\Flight\Domain\Entity\FlightCollection[] $connections
     */
    public function __construct(
        private array $connections = [],
    ) {
    }

    /**
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     * @param string $key
     *
     * @return void
     */
    public function setFlightConnections(string $key, FlightCollection $flightCollection): void
    {
        $this->connections[$key] = $flightCollection;
    }

    /**
     * @param string $identifier
     *
     * @return void
     */
    public function initNewIdentifier(string $identifier): void
    {
        $this->connections[$identifier] = new FlightCollection();
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function addFlightToCollection(string $key, Flight $flight): void
    {
        $this->connections[$key]->addFlight($flight);
    }

    /**
     * @return \App\Flight\Domain\Entity\FlightCollection[]
     */
    public function getFlightConnections(): array
    {
        return $this->connections;
    }

    /**
     * @param string $key
     *
     * @return \App\Flight\Domain\Entity\FlightCollection
     */
    public function getFlightConnection(string $key): FlightCollection
    {
        return $this->connections[$key];
    }
}
