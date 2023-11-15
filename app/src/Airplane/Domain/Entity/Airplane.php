<?php

declare(strict_types=1);

namespace App\Airplane\Domain\Entity;

use App\Airline\Domain\Entity\Airline;

final class Airplane
{
    /**
     * @param string  $registration
     * @param Airline $airline
     */
    public function __construct(private readonly string $registration, private readonly Airline $airline)
    {
    }

    /**
     * @return string
     */
    public function getRegistration(): string
    {
        return $this->registration;
    }

    /**
     * @return Airline
     */
    public function getAirline(): Airline
    {
        return $this->airline;
    }
}
