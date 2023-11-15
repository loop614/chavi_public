<?php

declare(strict_types=1);

namespace App\Flight;

use App\Flight\Transfer\FlightWrapperTransfer;

interface FlightFacadeInterface
{
    /**
     * @param array $input
     *
     * @return \App\Flight\Transfer\FlightWrapperTransfer
     */
    public function createFlight(array $input): FlightWrapperTransfer;
}
