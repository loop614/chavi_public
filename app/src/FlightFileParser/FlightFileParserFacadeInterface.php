<?php

declare(strict_types=1);

namespace App\FlightFileParser;

use App\FlightFileParser\Transfer\ParseResultTransfer;

interface FlightFileParserFacadeInterface
{
    /**
     * @param array $file
     *
     * @return \App\FlightFileParser\Transfer\ParseResultTransfer
     */
    public function parseFileToCollection(array $file): ParseResultTransfer;
}
