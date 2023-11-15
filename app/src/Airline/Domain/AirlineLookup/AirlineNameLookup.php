<?php

declare(strict_types=1);

namespace App\Airline\Domain\AirlineLookup;

use App\Airline\Domain\AirlineLookup\Exception\AirlineNameNotFoundException;

class AirlineNameLookup implements AirlineNameLookupInterface
{
    /**
     * @var array<string, string>
     */
    private const AIRLINE_REGISTRATION_TO_NAME = [
        'HA-AAA' => 'Alpha Airlines',
        'HA-AAB' => 'Alpha Airlines',
        'HA-AAC' => 'Alpha Airline',

        'D-AAA' => 'Delta Freight',
        'D-AAB' => 'Delta Freight',
        'D-AAC' => 'Delta Freight',

        'OO-AAA' => 'Oscar Air',
        'OO-AAB' => 'Oscar Air',
        'OO-AAC' => 'Oscar Air',
    ];

    /**
     * @param string $registration
     *
     * @return string
     */
    public function findAirlineNameByRegistration(string $registration): string
    {
        if (array_key_exists($registration, self::AIRLINE_REGISTRATION_TO_NAME)) {
            return self::AIRLINE_REGISTRATION_TO_NAME[$registration];
        }

        throw new AirlineNameNotFoundException('Missing registration mapping.');
    }
}
