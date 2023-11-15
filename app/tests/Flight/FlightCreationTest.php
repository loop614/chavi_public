<?php

declare(strict_types=1);

namespace App\Tests\Flight;

use App\Flight\FlightFacade;
use App\Flight\FlightFacadeInterface;
use PHPUnit\Framework\TestCase;

class FlightCreationTest extends TestCase
{
    /**
     * @var \App\Flight\FlightFacadeInterface
     */
    private readonly FlightFacadeInterface $sut;

    public function __construct()
    {
        $this->sut = new FlightFacade();

        parent::__construct();
    }

    /**
     * @return void
     */
    public function testValidCreation(): void
    {
        $input = $this->getInputData("./tests/Flight/Data/validFlightCreation.json");
        $flightWrapper = $this->sut->createFlight($input);
        $this->assertNotNull($flightWrapper->getFlight());
        $this->assertEquals(0, count($flightWrapper->getErrors()));
    }

    /**
     * @return void
     */
    public function testCreationWithMissingFromTo(): void
    {
        $input = $this->getInputData("./tests/Flight/Data/missingFromToFlightCreation.json");
        $flightWrapper = $this->sut->createFlight($input);

        $this->assertNull($flightWrapper->getFlight());
        $this->assertEquals(2, count($flightWrapper->getErrors()));
        $this->assertEquals("Mandatory field 'from' is not set", $flightWrapper->getErrors()[0]);
        $this->assertEquals("Mandatory field 'to' is not set", $flightWrapper->getErrors()[1]);
    }

    /**
     * @return void
     */
    public function testCreationWithWrongFormat(): void
    {
        $input = $this->getInputData("./tests/Flight/Data/wrongFormatFlightCreation.json");
        $flightWrapper = $this->sut->createFlight($input);
        $this->assertNull($flightWrapper->getFlight());

        $this->assertEquals(3, count($flightWrapper->getErrors()));
        $this->assertEquals(
            "Registration Field is not in expected format. Expected Two numbers - Three upper case letters",
            $flightWrapper->getErrors()[0]
        );
        $this->assertEquals(
            "Destination Field 'from' is not in expected format. Three upper case letters expected",
            $flightWrapper->getErrors()[1]
        );
        $this->assertEquals(
            "Destination Field 'to' is not in expected format. Three upper case letters expected",
            $flightWrapper->getErrors()[2]
        );
    }

    /**
     * @return void
     */
    public function testCreationWithBadDates(): void
    {
        $input = $this->getInputData("./tests/Flight/Data/badDateFlightCreation.json");
        $flightWrapper = $this->sut->createFlight($input);
        $this->assertNull($flightWrapper->getFlight());
        $this->assertEquals(4, count($flightWrapper->getErrors()));

        $this->assertEquals(
            "Date Field scheduled_start is not in expected format and cant be parsed to Datetime",
            $flightWrapper->getErrors()[0]
        );
        $this->assertEquals(
            "Date Field scheduled_end is not in expected format and cant be parsed to Datetime",
            $flightWrapper->getErrors()[1]
        );
        $this->assertEquals(
            "Date Field actual_start is not in expected format and cant be parsed to Datetime",
            $flightWrapper->getErrors()[2]
        );
        $this->assertEquals(
            "Date Field actual_end is not in expected format and cant be parsed to Datetime",
            $flightWrapper->getErrors()[3]
        );
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    private function getInputData(string $filePath): array
    {
        $file = fopen($filePath, "r");
        $fileContent = fread($file, filesize($filePath));

        return json_decode($fileContent, true);
    }
}
