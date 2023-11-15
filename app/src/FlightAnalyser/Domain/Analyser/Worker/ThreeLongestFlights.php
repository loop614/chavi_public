<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser\Worker;

use App\Flight\Domain\Entity\Flight;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;
use DateInterval;

class ThreeLongestFlights extends AbstractFlightAnalyserWorker implements FlightAnalyserWorkerInterface
{
    /**
     * @param array $threeLongest
     */
    public function __construct(private array $threeLongest = [])
    {
        foreach ([0, 1, 2] as $i) {
            $this->threeLongest[] = [
                "actualDuration" => new DateInterval("PT0S"),
                "flight" => null,
            ];
        }
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function analyse(Flight $flight): void
    {
        $actualStart = $flight->getActualStart();
        $actualEnd = $flight->getActualEnd();
        $timeDiff = $actualStart->diff($actualEnd);

        $maxPos = -1;
        foreach ([0, 1, 2] as $i) {
            if ($this->isTimeIntervalBigger($timeDiff, $this->threeLongest[$i]["actualDuration"])) {
                $maxPos = $i;
                break;
            }
        }
        if (in_array($maxPos, [0, 1, 2])) {
            $this->moveArrayMembers($maxPos, $timeDiff, $flight);
        }
    }

    /**
     * @param \App\FlightAnalyser\Transfer\FlightAnalysisTransfer $analysis
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function hydrate(FlightAnalysisTransfer $analysis): FlightAnalysisTransfer
    {
        foreach ($this->threeLongest as $longest) {
            if ($longest["flight"] !== null) {
                $analysis->addLongestFlight($longest["flight"]);
            }
        }

        return $analysis;
    }

    /**
     * @param int $maxPos
     * @param \DateInterval $timeDiff
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    private function moveArrayMembers(int $maxPos, DateInterval $timeDiff, Flight $flight): void
    {
        $newThreeLongest = [];
        $newMember = [
            "actualDuration" => $timeDiff,
            "flight" => $flight,
        ];

        $oldArrayPivot = 0;
        foreach ([0, 1, 2] as $i) {
            if ($i === $maxPos) {
                $newThreeLongest[] = $newMember;
                continue;
            }
            $nextMember = [];
            $nextMember["actualDuration"] = $this->threeLongest[$oldArrayPivot]["actualDuration"];
            $nextMember["flight"] = null;
            if ($this->threeLongest[$oldArrayPivot]["flight"] !== null) {
                $nextMember["flight"] = $this->threeLongest[$oldArrayPivot]["flight"];
            }
            $newThreeLongest[] = $nextMember;
            $oldArrayPivot++;
        }

        $this->threeLongest = $newThreeLongest;
    }
}
