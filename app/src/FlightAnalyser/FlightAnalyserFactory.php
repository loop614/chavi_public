<?php

declare(strict_types=1);

namespace App\FlightAnalyser;

use App\FlightAnalyser\Domain\Analyser\FlightCollectionAnalyser;
use App\FlightAnalyser\Domain\Analyser\FlightCollectionAnalyserInterface;
use App\FlightAnalyser\Domain\Analyser\Worker\AirlinesMostMissedLandings;
use App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface;
use App\FlightAnalyser\Domain\Analyser\Worker\MostOvernightAirplaneOnDestinations;
use App\FlightAnalyser\Domain\Analyser\Worker\MostOvernightsOnAirplane;
use App\FlightAnalyser\Domain\Analyser\Worker\ThreeLongestFlights;

class FlightAnalyserFactory
{
    /**
     * @return \App\FlightAnalyser\Domain\Analyser\FlightCollectionAnalyserInterface
     */
    public function createFlightCollectionAnalyser(): FlightCollectionAnalyserInterface
    {
        return new FlightCollectionAnalyser($this->createFlightAnalyserWorkers());
    }

    /**
     * @return \App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface[]
     */
    private function createFlightAnalyserWorkers(): array
    {
        return [
            $this->createThreeLongestFlights(),
            $this->createAirlinesMostMissedScheduledLandings(),
            $this->createMostOvernightsOnAirplane(),
            $this->createMostOvernightAirplaneOnDestinations(),
        ];
    }

    /**
     * @return \App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface
     */
    private function createThreeLongestFlights(): FlightAnalyserWorkerInterface
    {
        return new ThreeLongestFlights();
    }

    /**
     * @return \App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface
     */
    private function createAirlinesMostMissedScheduledLandings(): FlightAnalyserWorkerInterface
    {
        return new AirlinesMostMissedLandings();
    }

    /**
     * @return \App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface
     */
    private function createMostOvernightsOnAirplane(): FlightAnalyserWorkerInterface
    {
        return new MostOvernightsOnAirplane();
    }

    /**
     * @return \App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface
     */
    private function createMostOvernightAirplaneOnDestinations(): FlightAnalyserWorkerInterface
    {
        return new MostOvernightAirplaneOnDestinations();
    }
}
