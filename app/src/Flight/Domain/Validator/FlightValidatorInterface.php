<?php

declare(strict_types=1);

namespace App\Flight\Domain\Validator;

use App\Flight\Transfer\FlightBuilderTransfer;

interface FlightValidatorInterface
{
    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    public function findErrors(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer = new FlightBuilderTransfer()
    ): FlightBuilderTransfer;
}
