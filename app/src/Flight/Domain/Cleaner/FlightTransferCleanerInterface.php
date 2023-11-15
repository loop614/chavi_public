<?php

declare(strict_types=1);

namespace App\Flight\Domain\Cleaner;

use App\Flight\Transfer\FlightBuilderTransfer;

interface FlightTransferCleanerInterface
{
    /**
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    public function clean(FlightBuilderTransfer $flightTransfer): FlightBuilderTransfer;
}
