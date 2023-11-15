<?php

declare(strict_types=1);

namespace App\FlightFileParser;

use App\Flight\FlightFacade;
use App\Flight\FlightFacadeInterface;
use App\FlightFileParser\Domain\FileParser\FlightFileParser;
use App\FlightFileParser\Domain\FileParser\FlightFileParserInterface;

class FlightFileParserFactory
{
    /**
     * @return \App\FlightFileParser\Domain\FileParser\FlightFileParserInterface
     */
    public function createFlightFileParser(): FlightFileParserInterface
    {
        return new FlightFileParser($this->createFlightFacade());
    }

    /**
     * @return \App\Flight\FlightFacadeInterface
     */
    private function createFlightFacade(): FlightFacadeInterface
    {
        return new FlightFacade();
    }
}
