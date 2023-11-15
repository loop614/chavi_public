<?php

declare(strict_types=1);

namespace App\Flight\Domain\Cleaner;

use App\Flight\Transfer\FlightBuilderTransfer;

class AirlineNameCleaner implements FlightTransferCleanerInterface
{
    private const CLEAN_AIRLINE_NAME_MAP = [
        'Alpha Airline' => 'Alpha Airlines'
    ];

    /**
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    public function clean(FlightBuilderTransfer $flightTransfer): FlightBuilderTransfer
    {
        $airlineName = $flightTransfer->getAirplaneBuilderTransfer()
            ->getAirlineBuilderTransfer()
            ->getName();

        $newAirlineName = $this->cleanAirLineName($airlineName);
        $flightTransfer->getAirplaneBuilderTransfer()
            ->getAirlineBuilderTransfer()
            ->setName($newAirlineName);

        return $flightTransfer;
    }

    /**
     * @param string $airlineName
     *
     * @return string
     */
    private function cleanAirLineName(string $airlineName): string
    {
        return self::CLEAN_AIRLINE_NAME_MAP[$airlineName] ?? $airlineName;
    }
}
