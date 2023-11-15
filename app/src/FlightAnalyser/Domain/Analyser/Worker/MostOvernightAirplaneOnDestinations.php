<?php

declare(strict_types=1);

namespace App\FlightAnalyser\Domain\Analyser\Worker;

use App\Connection\Transfer\ConnectionCollectionTransfer;
use App\Flight\Domain\Entity\Flight;
use App\FlightAnalyser\Transfer\FlightAnalysisTransfer;

/**
 * was not in the task
 * this worker can connect flights from collection of the same airplane
 * if same airplane flies ZAG->BUD, then BUD->ZAG
 * he spent a night in BUD (landed < 00:00, take off > 00:00), we count +1 for BUD
 * returns destination where airplanes spent most nights
 */
class MostOvernightAirplaneOnDestinations extends AbstractFlightAnalyserWorker implements FlightAnalyserWorkerInterface
{
    public function __construct(
        private readonly ConnectionCollectionTransfer $connectionsCollection = new ConnectionCollectionTransfer()
    ) {
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight
     *
     * @return void
     */
    public function analyse(Flight $flight): void
    {
        $registration = $flight->getAirplane()->getRegistration();
        $to = $flight->getTo();
        $from = $flight->getFrom();
        $scheduledStart = $flight->getScheduledStart();
        $flightIdentifier = $registration . $from . $to . $scheduledStart->format(DATE_RFC3339_EXTENDED);
        $matchedFlightIdentifier = null;

        foreach ($this->connectionsCollection->getFlightConnections() as $identifier => $flightCollection) {
            $flightCollectionTail = $flightCollection->getLastCollectionElement();
            if ($this->areConnectedFlightsOfSameAirplane($flightCollectionTail, $flight)) {
                $matchedFlightIdentifier = $identifier;
                break;
            }
        }

        if ($matchedFlightIdentifier === null) {
            $this->connectionsCollection->initNewIdentifier($flightIdentifier);
            $matchedFlightIdentifier = $flightIdentifier;
        }

        $this->connectionsCollection->addFlightToCollection($matchedFlightIdentifier, $flight);
    }

    /**
     * @param \App\FlightAnalyser\Transfer\FlightAnalysisTransfer $analysis
     *
     * @return \App\FlightAnalyser\Transfer\FlightAnalysisTransfer
     */
    public function hydrate(FlightAnalysisTransfer $analysis): FlightAnalysisTransfer
    {
        $destinationToNights = [];
        foreach ($this->connectionsCollection->getFlightConnections() as $flightCollection) {
            $flights = $flightCollection->getFlights();
            foreach ($flights as $index => $flight) {
                if (!isset($flights[$index + 1])) {
                    break;
                }
                $nextFlight = $flights[$index + 1];
                if (!$this->isOverNightStay($flight->getActualEnd(), $nextFlight->getActualStart())) {
                    continue;
                }
                $destination = $flight->getTo();
                if (!isset($destinationToNights[$destination])) {
                    $destinationToNights[$destination] = 0;
                }
                $destinationToNights[$destination]++;
            }
        }

        $mostOverNightStayDestinationsArray = $this->getKeysWithLargestValue($destinationToNights);
        $analysis->setMostOvernightsAirplaneOnDestinations($mostOverNightStayDestinationsArray);

        return $analysis;
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight1
     * @param \App\Flight\Domain\Entity\Flight $flight2
     *
     * @return bool
     */
    private function areConnectedFlightsOfSameAirplane(Flight $flight1, Flight $flight2): bool
    {
        if ($flight1->getAirplane()->getRegistration() !== $flight2->getAirplane()->getRegistration()) {
            return false;
        }

        if ($flight1->getTo() !== $flight2->getFrom()) {
            return false;
        }

        if ($flight1->getActualEnd() > $flight2->getActualStart()) {
            return false;
        }

        return true;
    }
}
