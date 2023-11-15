<?php

declare(strict_types=1);

namespace App\Tests\FlightAnalyser;

use App\Flight\Domain\Entity\Flight;
use App\Flight\Domain\Entity\FlightCollection;
use App\Flight\FlightFacade;
use App\Flight\FlightFacadeInterface;
use App\FlightAnalyser\FlightAnalyserFacade;
use App\FlightAnalyser\FlightAnalyserFacadeInterface;
use PHPUnit\Framework\TestCase;

class FlightAnalyseTest extends TestCase
{
    /**
     * @var \App\FlightAnalyser\FlightAnalyserFacadeInterface
     */
    private readonly FlightAnalyserFacadeInterface $sut;

    /**
     * @var \App\Flight\FlightFacadeInterface
     */
    private readonly FlightFacadeInterface $flightFacade;

    public function __construct()
    {
        $this->flightFacade = new FlightFacade();
        $this->sut = new FlightAnalyserFacade();

        parent::__construct();
    }

    /**
     * @return void
     */
    public function testAnalyseExample1(): void
    {
        $input = $this->getInputData("./tests/FlightAnalyser/Data/analyseExample1.jsonl");
        $expectedLongest = $this->getInputData("./tests/FlightAnalyser/Data/analyseExampleLongest1.jsonl");
        $expectedThreeLongestFlights = $this->parseDataArrayToCollection($expectedLongest);
        $flightCollection = $this->parseDataArrayToCollection($input);
        $analysis = $this->sut->analyseFlightCollection($flightCollection);

        $threeLongestFlights = $analysis->getThreeLongestFlights();
        $airlinesMostMissedLandings = $analysis->getAirlinesMostMissedLandings();
        $mostOvernightStayDestinations = $analysis->getMostOvernightsPersonOnAirplaneDestinations();

        $this->assertEquals($threeLongestFlights->getFlightsCount(), 3);
        foreach ([0,1,2] as $i) {
            $this->assertFlightSame(
                $threeLongestFlights->getFlights()[$i],
                $expectedThreeLongestFlights->getFlights()[$i],
            );
        }
        $this->assertEquals($airlinesMostMissedLandings[0], "Delta Freight");
        $this->assertEquals($mostOvernightStayDestinations[0], "BUD");
    }

    /**
     * @return void
     */
    public function testAnalyseExample2(): void
    {
        $input = $this->getInputData("./tests/FlightAnalyser/Data/analyseExample2.jsonl");
        $expectedLongest = $this->getInputData("./tests/FlightAnalyser/Data/analyseExampleLongest2.jsonl");
        $expectedThreeLongestFlights = $this->parseDataArrayToCollection($expectedLongest);
        $flightCollection = $this->parseDataArrayToCollection($input);
        $analysis = $this->sut->analyseFlightCollection($flightCollection);

        $threeLongestFlights = $analysis->getThreeLongestFlights();
        $airlinesMostMissed = $analysis->getAirlinesMostMissedLandings();
        $mostOvernightStayDestinations = $analysis->getMostOvernightsPersonOnAirplaneDestinations();

        $this->assertEquals($threeLongestFlights->getFlightsCount(), 3);
        foreach ([0,1,2] as $i) {
            $this->assertFlightSame(
                $threeLongestFlights->getFlights()[$i],
                $expectedThreeLongestFlights->getFlights()[$i],
            );
        }
        $this->assertEquals($airlinesMostMissed[0], "Oscar Air");
        $this->assertEquals($mostOvernightStayDestinations[0], "BUD");
    }

    /**
     * @return void
     */
    public function testAnalyseExample3(): void
    {
        $input = $this->getInputData("./tests/FlightAnalyser/Data/analyseExample3.jsonl");
        $expectedLongest = $this->getInputData("./tests/FlightAnalyser/Data/analyseExampleLongest3.jsonl");
        $expectedThreeLongestFlights = $this->parseDataArrayToCollection($expectedLongest);
        $flightCollection = $this->parseDataArrayToCollection($input);

        $analysis = $this->sut->analyseFlightCollection($flightCollection);
        $threeLongestFlights = $analysis->getThreeLongestFlights();
        $airlinesMostMissed = $analysis->getAirlinesMostMissedLandings();
        $mostOvernightDestinations = $analysis->getMostOvernightsPersonOnAirplaneDestinations();

        $this->assertEquals($threeLongestFlights->getFlightsCount(), 3);
        foreach ([0,1,2] as $i) {
            $this->assertFlightSame(
                $threeLongestFlights->getFlights()[$i],
                $expectedThreeLongestFlights->getFlights()[$i],
            );
        }
        $this->assertEquals($airlinesMostMissed[0], "Delta Freight");
        $this->assertCount(0, $mostOvernightDestinations);
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    private function getInputData(string $filePath): array
    {
        $data = [];
        $file = file($filePath);
        foreach ($file as $line) {
            $data[] = json_decode($line, true);
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return \App\Flight\Domain\Entity\FlightCollection
     */
    private function parseDataArrayToCollection(array $data): FlightCollection
    {
        $flightCollection = new FlightCollection();
        foreach ($data as $line) {
            $flightWrapper = $this->flightFacade->createFlight($line);
            if (!$flightWrapper->isValid()) {
                continue;
            }
            $flightCollection->addFlight($flightWrapper->getFlight());
        }

        return $flightCollection;
    }

    /**
     * @param \App\Flight\Domain\Entity\Flight $flight1
     * @param \App\Flight\Domain\Entity\Flight $flight2
     *
     * @return void
     */
    private function assertFlightSame(Flight $flight1, Flight $flight2): void
    {
        $message = "Two flights are not equal: field %s does not match";
        $this->assertEquals($flight1->getFrom(), $flight2->getFrom(), sprintf($message, "from"));
        $this->assertEquals($flight1->getTo(), $flight2->getTo(), sprintf($message, "to"));
        $this->assertEquals(
            $flight1->getAirplane()->getRegistration(),
            $flight2->getAirplane()->getRegistration(),
            sprintf($message, "registration")
        );
        $this->assertEquals(
            $flight1->getAirplane()->getAirline()->getName(),
            $flight2->getAirplane()->getAirline()->getName(),
            sprintf($message, "airline name")
        );
    }
}
