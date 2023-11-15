<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser;

use App\Flight\Domain\Entity\FlightCollection;
use App\FlightAnalyser\Domain\Analyser\Worker\AbstractFlightAnalyserWorker;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

class FlightCollectionAnalyser extends AbstractFlightAnalyserWorker implements FlightCollectionAnalyserInterface
{
    /**
     * @param \App\FlightAnalyser\Domain\Analyser\Worker\FlightAnalyserWorkerInterface[] $analyserWorkers
     */
    public function __construct(private readonly array $analyserWorkers)
    {
    }

    /**
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function analyse(FlightCollection $flightCollection): FlightAnalysisTransfer
    {
        $analysis = new FlightAnalysisTransfer();
        foreach ($flightCollection->getFlights() as $flight) {
            foreach ($this->analyserWorkers as $analyserWorker) {
                $analyserWorker->analyse($flight);
            }
        }

        foreach ($this->analyserWorkers as $analyserWorker) {
            $analyserWorker->hydrate($analysis);
        }

        return $analysis;
    }
}
