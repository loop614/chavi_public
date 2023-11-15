<?php

declare(strict_types=1);

namespace App\Flight\Domain\Mapper;

use App\Airline\Domain\Entity\Airline;
use App\Airplane\Domain\Entity\Airplane;
use App\Flight\Domain\Entity\Flight;
use App\Flight\Transfer\FlightBuilderTransfer;
use App\Flight\Transfer\FlightWrapperTransfer;

class FlightMapper implements FlightMapperInterface
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
    ): FlightWrapperTransfer {
        $airplaneBuilderTransfer = $flightBuilderTransfer->getAirplaneBuilderTransfer();
        $airlineBuilderTransfer = $airplaneBuilderTransfer->getAirlineBuilderTransfer();
        $airline = new Airline($airlineBuilderTransfer->getName());
        $airplane = new Airplane($airplaneBuilderTransfer->getRegistration(), $airline);
        $flight = new Flight(
            $airplane,
            $flightBuilderTransfer->getFrom(),
            $flightBuilderTransfer->getTo(),
            $flightBuilderTransfer->getScheduledStart(),
            $flightBuilderTransfer->getScheduledEnd(),
            $flightBuilderTransfer->getActualStart(),
            $flightBuilderTransfer->getActualEnd(),
        );
        $flightEntityWrapperTransfer->setFlight($flight);

        return $flightEntityWrapperTransfer;
    }
}
