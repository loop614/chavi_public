<?php

declare(strict_types=1);

namespace App\FlightFileParser\Transfer;

use App\Flight\Domain\Entity\Flight;
use App\Flight\Domain\Entity\FlightCollection;

class ParseResultTransfer
{
    /**
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     * @param string[] $messages
     * @param int $countParsedWithError
     */
    public function __construct(
        private readonly FlightCollection $flightCollection = new FlightCollection(),
        public array $messages = [],
        public int $countParsedWithError = 0,
    ) {
    }

    /**
     * @return \App\Flight\Domain\Entity\FlightCollection
     */
    public function getFlightCollection(): FlightCollection
    {
        return $this->flightCollection;
    }

    /**
     * @return int
     */
    public function getFlightsCount(): int
    {
        return $this->flightCollection->getFlightsCount();
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function addFlight(Flight $flight): void
    {
        $this->flightCollection->addFlight($flight);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
