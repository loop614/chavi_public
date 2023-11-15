<?php

declare(strict_types=1);

namespace App\Airline;

final class AirlineFacade implements AirlineFacadeInterface
{
    /**
     * @var \App\Airline\AirlineFactory
     */
    private readonly AirlineFactory $factory;

    public function __construct()
    {
        $this->factory = new AirlineFactory();
    }

    /**
     *
     * Get airline name for code
     * RuntimeException if it does not exist
     *
     * @param string $registration
     *
     * @throws \App\Airline\Domain\AirlineLookup\Exception\AirlineNameNotFoundException
     *
     * @return string
     */
    public function findAirlineNameByRegistration(string $registration): string
    {
        return $this->factory
            ->createAirlineNameLookup()
            ->findAirlineNameByRegistration($registration);
    }
}
