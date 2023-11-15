<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser\Worker;

use DateInterval;
use DateTimeImmutable;

abstract class AbstractFlightAnalyserWorker
{
    /**
     * @param \DateInterval $di1
     * @param \DateInterval $di2
     *
     * @return bool
     */
    protected function isTimeIntervalBigger(DateInterval $di1, DateInterval $di2): bool
    {
        // @codingStandardsIgnoreStart
        if ($di1->y > $di2->y) { return true; } else if ($di1->y < $di2->y) { return false; }
        if ($di1->m > $di2->m) { return true; } else if ($di1->m < $di2->m) { return false; }
        if ($di1->d > $di2->d) { return true; } else if ($di1->d < $di2->d) { return false; }
        if ($di1->h > $di2->h) { return true; } else if ($di1->h < $di2->h) { return false; }
        if ($di1->i > $di2->i) { return true; } else if ($di1->i < $di2->i) { return false; }
        if ($di1->s > $di2->s) { return true; } else if ($di1->s < $di2->s) { return false; }
        if ($di1->f > $di2->f) { return true; } else if ($di1->f < $di2->f) { return false; }
        return false;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @param array $dictionary
     *
     * @return array
     */
    protected function getKeysWithLargestValue(array $dictionary): array
    {
        $maxMissed = 0;
        $keysWithMaxValue = [];
        foreach ($dictionary as $key => $value) {
            if ($value > $maxMissed) {
                $maxMissed = $value;
                $keysWithMaxValue = [$key];
                continue;
            }
            if ($value === $maxMissed) {
                $keysWithMaxValue[] = $key;
            }
        }

        return $keysWithMaxValue;
    }

    /**
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return bool
     */
    protected function isOverNightStay(DateTimeImmutable $start, DateTimeImmutable $end): bool
    {
        return $start->modify("+1 day")->format("d") === $end->format("d");
    }
}
