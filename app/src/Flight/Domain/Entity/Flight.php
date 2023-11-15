<?php

declare(strict_types=1);

namespace App\Flight\Domain\Entity;

use App\Airplane\Domain\Entity\Airplane;

final class Flight
{
    /**
     * @param Airplane $airplane
     * @param string $from
     * @param string $to
     * @param \DateTimeImmutable $scheduledStart
     * @param \DateTimeImmutable $scheduledEnd
     * @param \DateTimeImmutable $actualStart
     * @param \DateTimeImmutable $actualEnd
     */
    public function __construct(
        private readonly Airplane $airplane,
        private readonly string $from,
        private readonly string $to,
        private readonly \DateTimeImmutable $scheduledStart,
        private readonly \DateTimeImmutable $scheduledEnd,
        private readonly \DateTimeImmutable $actualStart,
        private readonly \DateTimeImmutable $actualEnd,
    ) {
    }

    /**
     * @return Airplane
     */
    public function getAirplane(): Airplane
    {
        return $this->airplane;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getScheduledStart(): \DateTimeImmutable
    {
        return $this->scheduledStart;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getScheduledEnd(): \DateTimeImmutable
    {
        return $this->scheduledEnd;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getActualStart(): \DateTimeImmutable
    {
        return $this->actualStart;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getActualEnd(): \DateTimeImmutable
    {
        return $this->actualEnd;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return
            $this->getAirplane()->getRegistration() . " " .
            $this->getFrom() . "->" .
            $this->getTo() . "  Scheduled: Start: " .
            $this->getScheduledStart()->format(DATE_RFC3339_EXTENDED) . " End: " .
            $this->getScheduledEnd()->format(DATE_RFC3339_EXTENDED) . "  Actual: Start: " .
            $this->getActualStart()->format(DATE_RFC3339_EXTENDED) . " End: " .
            $this->getActualEnd()->format(DATE_RFC3339_EXTENDED);
    }
}
