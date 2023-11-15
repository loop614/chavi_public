<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Transfer;

use App\Flight\Domain\Entity\Flight;
use App\Flight\Domain\Entity\FlightCollection;

final class FlightAnalysisTransfer
{
    /**
     * @param \App\Flight\Domain\Entity\FlightCollection|null $threeLongestFlights
     * @param array|null $airlinesMostMissedLandings
     * @param array|null $mostOvernightsPersonOnAirplaneDestinations
     */
    public function __construct(
        private ?FlightCollection $threeLongestFlights = new FlightCollection(),
        private ?array $airlinesMostMissedLandings = [],
        private ?array $mostOvernightsPersonOnAirplaneDestinations = [],
        private ?array $mostOvernightsAirplaneOnDestinations = [],
    ) {
    }

    /**
     * @return \App\Flight\Domain\Entity\FlightCollection|null
     */
    public function getThreeLongestFlights(): ?FlightCollection
    {
        return $this->threeLongestFlights;
    }

    /**
     * @return string[]|null
     */
    public function getAirlinesMostMissedLandings(): ?array
    {
        return $this->airlinesMostMissedLandings;
    }

    /**
     * @return string[]|null
     */
    public function getMostOvernightsPersonOnAirplaneDestinations(): ?array
    {
        return $this->mostOvernightsPersonOnAirplaneDestinations;
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function addLongestFlight(Flight $flight): void
    {
        $this->threeLongestFlights->addFlight($flight);
    }

    /**
     * @param array $airlinesMostMissedLandings
     *
     * @return void
     */
    public function setAirlinesMostMissedLandings(array $airlinesMostMissedLandings): void
    {
        $this->airlinesMostMissedLandings = $airlinesMostMissedLandings;
    }

    /**
     * @param array $mostOvernightsPersonOnAirplaneDestinations
     *
     * @return void
     */
    public function setMostOvernightsPersonOnAirplaneDestinations(
        array $mostOvernightsPersonOnAirplaneDestinations
    ): void {
        $this->mostOvernightsPersonOnAirplaneDestinations = $mostOvernightsPersonOnAirplaneDestinations;
    }

    /**
     * @return array|null
     */
    public function getMostOvernightsAirplaneOnDestinations(): ?array
    {
        return $this->mostOvernightsAirplaneOnDestinations;
    }

    /**
     * @param array $mostOvernightsAirplaneOnDestinations
     *
     * @return void
     */
    public function setMostOvernightsAirplaneOnDestinations(array $mostOvernightsAirplaneOnDestinations): void
    {
        $this->mostOvernightsAirplaneOnDestinations = $mostOvernightsAirplaneOnDestinations;
    }
}
