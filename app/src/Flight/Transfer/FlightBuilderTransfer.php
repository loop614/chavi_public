<?php

declare(strict_types=1);

namespace App\Flight\Transfer;

use App\Airplane\Transfer\AirplaneBuilderTransfer;
use DateTimeImmutable;

final class FlightBuilderTransfer
{
    public function __construct(
        private ?AirplaneBuilderTransfer $airplaneBuilderTransfer = null,
        private ?string $from = null,
        private ?string $to = null,
        private ?DateTimeImmutable $scheduledStart = null,
        private ?DateTimeImmutable $scheduledEnd = null,
        private ?DateTimeImmutable $actualStart = null,
        private ?DateTimeImmutable $actualEnd = null,
        private array $errors = [],
    ) {
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
     * @param string $error
     *
     * @return void
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return \App\Airplane\Transfer\AirplaneBuilderTransfer|null
     */
    public function getAirplaneBuilderTransfer(): ?AirplaneBuilderTransfer
    {
        return $this->airplaneBuilderTransfer;
    }

    /**
     * @param \App\Airplane\Transfer\AirplaneBuilderTransfer $airplaneBuilderTransfer
     *
     * @return void
     */
    public function setAirplaneBuilderTransfer(AirplaneBuilderTransfer $airplaneBuilderTransfer): void
    {
        $this->airplaneBuilderTransfer = $airplaneBuilderTransfer;
    }

    /**
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return void
     */
    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    /**
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return void
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getScheduledStart(): ?DateTimeImmutable
    {
        return $this->scheduledStart;
    }

    /**
     * @param \DateTimeImmutable $scheduledStart
     *
     * @return void
     */
    public function setScheduledStart(DateTimeImmutable $scheduledStart): void
    {
        $this->scheduledStart = $scheduledStart;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getScheduledEnd(): ?DateTimeImmutable
    {
        return $this->scheduledEnd;
    }

    /**
     * @param \DateTimeImmutable $scheduledEnd
     *
     * @return void
     */
    public function setScheduledEnd(DateTimeImmutable $scheduledEnd): void
    {
        $this->scheduledEnd = $scheduledEnd;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getActualStart(): ?DateTimeImmutable
    {
        return $this->actualStart;
    }

    /**
     * @param \DateTimeImmutable $actualStart
     *
     * @return void
     */
    public function setActualStart(DateTimeImmutable $actualStart): void
    {
        $this->actualStart = $actualStart;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getActualEnd(): ?DateTimeImmutable
    {
        return $this->actualEnd;
    }

    /**
     * @param \DateTimeImmutable $actualEnd
     *
     * @return void
     */
    public function setActualEnd(DateTimeImmutable $actualEnd): void
    {
        $this->actualEnd = $actualEnd;
    }
}
