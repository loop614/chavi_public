<?php

declare(strict_types=1);

namespace App\Flight\Domain\Mapper;

use App\Flight\Transfer\FlightBuilderTransfer;
use App\Flight\Transfer\FlightWrapperTransfer;

interface FlightMapperInterface
{
    /**
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     * @param \App\Flight\Transfer\FlightWrapperTransfer $flightEntityWrapperTransfer
     *
     * @return \App\Flight\Transfer\FlightWrapperTransfer
     */
    public function map(
        FlightBuilderTransfer $flightBuilderTransfer,
        FlightWrapperTransfer $flightEntityWrapperTransfer = new FlightWrapperTransfer(),
    ): FlightWrapperTransfer;
}
