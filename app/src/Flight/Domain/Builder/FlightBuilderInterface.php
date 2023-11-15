<?php

declare(strict_types=1);

namespace App\Flight\Domain\Builder;

use App\Flight\Transfer\FlightWrapperTransfer;

interface FlightBuilderInterface
{
    /**
     * @param array $input
     *
     * @return \App\Flight\Transfer\FlightWrapperTransfer
     */
    public function buildFlight(array $input): FlightWrapperTransfer;
}
