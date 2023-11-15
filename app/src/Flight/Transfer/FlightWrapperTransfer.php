<?php

declare(strict_types=1);

namespace App\Flight\Transfer;

use App\Flight\Domain\Entity\Flight;

final class FlightWrapperTransfer
{
    public function __construct(
        private ?Flight $flight = null,
        private array $errors = [],
    ) {
    }

    /**
     * @return \App\Flight\Domain\Entity\Flight|null
     */
    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function setFlight(Flight $flight): void
    {
        $this->flight = $flight;
    }

    /**
     * @return boolean
     */
    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param string $error
     *
     * @return void
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return
            $this->getFlight()->getAirplane()->getRegistration() . " " .
            $this->getFlight()->getFrom() . "->" .
            $this->getFlight()->getTo() . "  Scheduled: Start: " .
            $this->getFlight()->getScheduledStart()->format(DATE_RFC3339_EXTENDED) . " End: " .
            $this->getFlight()->getScheduledEnd()->format(DATE_RFC3339_EXTENDED) . "  Actual: Start: " .
            $this->getFlight()->getActualStart()->format(DATE_RFC3339_EXTENDED) . " End: " .
            $this->getFlight()->getActualEnd()->format(DATE_RFC3339_EXTENDED);
    }
}
