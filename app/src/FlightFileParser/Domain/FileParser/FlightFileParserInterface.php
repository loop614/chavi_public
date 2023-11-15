<?php

declare(strict_types=1);

namespace App\FlightFileParser\Domain\FileParser;

use App\FlightFileParser\Transfer\ParseResultTransfer;

interface FlightFileParserInterface
{
    /**
     * @param array $file
     *
     * @return \App\FlightFileParser\Transfer\ParseResultTransfer
     */
    public function parseFileToCollection(array $file): ParseResultTransfer;
}
