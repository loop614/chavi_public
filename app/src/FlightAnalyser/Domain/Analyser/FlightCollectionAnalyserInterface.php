<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser;

use App\Flight\Domain\Entity\FlightCollection;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

interface FlightCollectionAnalyserInterface
{
    /**
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function analyse(FlightCollection $flightCollection): FlightAnalysisTransfer;
}
