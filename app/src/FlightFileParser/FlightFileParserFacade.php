<?php

declare(strict_types=1);

namespace App\FlightFileParser;

use App\FlightFileParser\Transfer\ParseResultTransfer;

final class FlightFileParserFacade implements FlightFileParserFacadeInterface
{
    /**
     * @var \App\FlightFileParser\FlightFileParserFactory
     */
    private readonly FlightFileParserFactory $factory;

    public function __construct()
    {
        $this->factory = new FlightFileParserFactory();
    }

    /**
     * @param array $file
     *
     * @return \App\FlightFileParser\Transfer\ParseResultTransfer
     */
    public function parseFileToCollection(array $file): ParseResultTransfer
    {
        return $this->factory
            ->createFlightFileParser()
            ->parseFileToCollection($file);
    }
}
