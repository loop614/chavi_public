<?php

declare(strict_types=1);

namespace App\FlightAnalyser;

use App\Flight\Domain\Entity\FlightCollection;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

class FlightAnalyserFacade implements FlightAnalyserFacadeInterface
{
    /**
     * @var \App\FlightAnalyser\FlightAnalyserFactory
     */
    private readonly FlightAnalyserFactory $factory;

    public function __construct()
    {
        $this->factory = new FlightAnalyserFactory();
    }

    /**
     * Get FlightAnalysisTransfer for provided FlightCollection
     *
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function analyseFlightCollection(FlightCollection $flightCollection): FlightAnalysisTransfer
    {
        return $this->factory
            ->createFlightCollectionAnalyser()
            ->analyse($flightCollection);
    }
}
