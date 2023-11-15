<?php

declare(strict_types=1);

namespace App\Airline;

interface AirlineFacadeInterface
{
    /**
     *
     * @param string $registration
     *
     * @throws \App\Airline\Domain\AirlineLookup\Exception\AirlineNameNotFoundException
     *
     * @return string
     */
    public function findAirlineNameByRegistration(string $registration): string;
}
