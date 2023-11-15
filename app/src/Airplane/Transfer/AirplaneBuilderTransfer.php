<?php

declare(strict_types=1);

namespace App\Airplane\Transfer;

use App\Airline\Transfer\AirlineBuilderTransfer;

final class AirplaneBuilderTransfer
{
    /**
     * @param string|null $registration
     * @param \App\Airline\Transfer\AirlineBuilderTransfer|null $airlineBuilderTransfer
     */
    public function __construct(
        private ?string $registration = null,
        private ?AirlineBuilderTransfer $airlineBuilderTransfer = null
    ) {
    }

    /**
     * @return string|null
     */
    public function getRegistration(): ?string
    {
        return $this->registration;
    }

    /**
     * @param string $registration
     *
     * @return void
     */
    public function setRegistration(string $registration): void
    {
        $this->registration = $registration;
    }

    /**
     * @return \App\Airline\Transfer\AirlineBuilderTransfer|null
     */
    public function getAirlineBuilderTransfer(): ?AirlineBuilderTransfer
    {
        return $this->airlineBuilderTransfer;
    }

    /**
     * @param \App\Airline\Transfer\AirlineBuilderTransfer $airline
     *
     * @return void
     */
    public function setAirlineBuilderTransfer(AirlineBuilderTransfer $airline): void
    {
        $this->airlineBuilderTransfer = $airline;
    }
}
