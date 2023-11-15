<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser\Worker;

use App\Flight\Domain\Entity\Flight;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

interface FlightAnalyserWorkerInterface
{
    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function analyse(Flight $flight): void;

    /**
     * @param \App\FlightAnalyser\Transfer\FlightAnalysisTransfer $analysis
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function hydrate(FlightAnalysisTransfer $analysis): FlightAnalysisTransfer;
}
