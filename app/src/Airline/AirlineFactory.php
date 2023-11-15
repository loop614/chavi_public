<?php

declare(strict_types=1);

namespace App\Airline;

use App\Airline\Domain\AirlineLookup\AirlineNameLookup;

final class AirlineFactory
{
    /**
     * @return \App\Airline\Domain\AirlineLookup\AirlineNameLookup
     */
    public function createAirlineNameLookup(): AirlineNameLookup
    {
        return new AirlineNameLookup();
    }
}
