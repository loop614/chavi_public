<?php

declare(strict_types=1);

namespace App\Airline\Domain\AirlineLookup;

interface AirlineNameLookupInterface
{
    /**
     * @param string $registration
     *
     * @return string
     */
    public function findAirlineNameByRegistration(string $registration): string;
}
