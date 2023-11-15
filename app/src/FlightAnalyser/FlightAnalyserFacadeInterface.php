<?php

declare(strict_types=1);

namespace App\FlightAnalyser;

use App\Flight\Domain\Entity\FlightCollection;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

interface FlightAnalyserFacadeInterface
{
    /**
     * Get FlightAnalysisTransfer for provided FlightCollection
     *
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function analyseFlightCollection(FlightCollection $flightCollection): FlightAnalysisTransfer;
}
