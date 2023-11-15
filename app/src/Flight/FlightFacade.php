<?php

declare(strict_types=1);

namespace App\Flight;

use App\Flight\Transfer\FlightWrapperTransfer;

final class FlightFacade implements FlightFacadeInterface
{
    /**
     * @var \App\Flight\FlightFactory
     */
    private readonly FlightFactory $factory;

    public function __construct()
    {
        $this->factory = new FlightFactory();
    }

    /**
     * Get FlightWrapperTransfer with Flight if provided valid input
     * For more info on validation check Validator/FlightValidator
     * For invalid inputs, check errors on FlightWrapperTransfer
     *
     * @param array $input
     *
     * @return \App\Flight\Transfer\FlightWrapperTransfer
     */
    public function createFlight(array $input): FlightWrapperTransfer
    {
        return $this->factory
            ->createFlightBuilder()
            ->buildFlight($input);
    }
}
