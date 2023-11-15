<?php

declare(strict_types=1);

namespace App\Flight\Domain\Entity;

final class FlightCollection
{
    /**
     * @param \App\Flight\Domain\Entity\Flight[] $flights
     */
    public function __construct(
        private array $flights = [],
    ) {
    }

    /**
     * @return \App\Flight\Domain\Entity\Flight[]
     */
    public function getFlights(): array
    {
        return $this->flights;
    }

    /**
     * @return int
     */
    public function getFlightsCount(): int
    {
        return count($this->flights);
    }

    /**
     * @return \App\Flight\Domain\Entity\Flight
     */
    public function getLastCollectionElement(): Flight
    {
        return $this->flights[count($this->flights) - 1];
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function addFlight(Flight $flight): void
    {
        $this->flights[] = $flight;
    }
}
