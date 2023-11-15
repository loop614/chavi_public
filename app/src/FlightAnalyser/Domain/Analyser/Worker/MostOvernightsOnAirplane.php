<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser\Worker;

use App\Flight\Domain\Entity\Flight;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

class MostOvernightsOnAirplane extends AbstractFlightAnalyserWorker implements FlightAnalyserWorkerInterface
{
    /**
     * @param array $destinationToOvernight
     */
    public function __construct(
        private array $destinationToOvernight = []
    ) {
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function analyse(Flight $flight): void
    {
        $actualStart = $flight->getActualStart();
        $actualEnd = $flight->getActualEnd();
        $destination = $flight->getTo();
        if ($this->isOverNightStay($actualStart, $actualEnd)) {
            if (!isset($this->destinationToOvernight[$destination])) {
                $this->destinationToOvernight[$destination] = 0;
            }
            $this->destinationToOvernight[$destination]++;
        }
    }

    /**
     * @param \App\FlightAnalyser\Transfer\FlightAnalysisTransfer $analysis
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function hydrate(FlightAnalysisTransfer $analysis): FlightAnalysisTransfer
    {
        $mostOverNightStayDestinationsArray = $this->getKeysWithLargestValue($this->destinationToOvernight);
        $analysis->setMostOvernightsPersonOnAirplaneDestinations($mostOverNightStayDestinationsArray);

        return $analysis;
    }
}
