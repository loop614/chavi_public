<?php

declare(strict_types=1);

namespace App\FlightFileParser\Domain\FileParser;

use App\Flight\FlightFacadeInterface;
use App\FlightFileParser\Transfer\ParseResultTransfer;

class FlightFileParser implements FlightFileParserInterface
{
    public function __construct(private readonly FlightFacadeInterface $flightFacade)
    {
    }

    /**
     * @param array $file
     *
     * @return \App\FlightFileParser\Transfer\ParseResultTransfer
     */
    public function parseFileToCollection(array $file): ParseResultTransfer
    {
        $parseResult = new ParseResultTransfer();
        foreach ($file as $line) {
            $flightArray = json_decode($line, true);
            $flightWrapper = $this->flightFacade->createFlight($flightArray);
            if (!$flightWrapper->isValid() || $flightWrapper->getFlight() === null) {
                $parseResult->countParsedWithError++;
                continue;
            }
            $parseResult->addFlight($flightWrapper->getFlight());
        }

        return $parseResult;
    }
}
