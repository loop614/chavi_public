<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser\Worker;

use App\Flight\Domain\Entity\Flight;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;
use DateInterval;

class AirlinesMostMissedLandings extends AbstractFlightAnalyserWorker implements FlightAnalyserWorkerInterface
{
    private DateInterval $fiveMinutes;

    /**
     * @param array $airlineToMissed
     */
    public function __construct(private array $airlineToMissed = [])
    {
        $this->fiveMinutes = new DateInterval('PT5M');
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function analyse(Flight $flight): void
    {
        $scheduledEnd = $flight->getScheduledEnd();
        $actualEnd = $flight->getActualEnd();
        $airlineName = $flight->getAirplane()->getAirline()->getName();
        $timeDiff = $scheduledEnd->diff($actualEnd);
        if ($this->isTimeIntervalBigger($timeDiff, $this->fiveMinutes)) {
            if (!isset($this->airlineToMissed[$airlineName])) {
                $this->airlineToMissed[$airlineName] = 0;
            }
            $this->airlineToMissed[$airlineName]++;
        }
    }

    /**
     * @param \App\FlightAnalyser\Transfer\FlightAnalysisTransfer $analysis
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function hydrate(FlightAnalysisTransfer $analysis): FlightAnalysisTransfer
    {
        $airLinesMostMissedLandingsArray = $this->getKeysWithLargestValue($this->airlineToMissed);
        $analysis->setAirlinesMostMissedLandings($airLinesMostMissedLandingsArray);

        return $analysis;
    }
}
