<?php

declare(strict_types=1);

namespace App\Flight;

use App\Airline\AirlineFacade;
use App\Airline\AirlineFacadeInterface;
use App\Flight\Domain\Builder\FlightBuilder;
use App\Flight\Domain\Builder\FlightBuilderInterface;
use App\Flight\Domain\Cleaner\AirlineNameCleaner;
use App\Flight\Domain\Cleaner\FlightTransferCleanerInterface;
use App\Flight\Domain\Mapper\FlightMapper;
use App\Flight\Domain\Mapper\FlightMapperInterface;
use App\Flight\Domain\Validator\FlightValidator;
use App\Flight\Domain\Validator\FlightValidatorInterface;
use App\FlightAnalyser\FlightAnalyserFacade;
use App\FlightAnalyser\FlightAnalyserFacadeInterface;
use App\FlightFileParser\FlightFileParserFacade;
use App\FlightFileParser\FlightFileParserFacadeInterface;

final class FlightFactory
{
    /**
     * @return \App\Flight\Domain\Builder\FlightBuilderInterface
     */
    public function createFlightBuilder(): FlightBuilderInterface
    {
        return new FlightBuilder(
            $this->createFlightValidator(),
            $this->createFlightBuilderTransferCleaners(),
            $this->createFlightMapper(),
        );
    }

    /**
     * @return \App\FlightAnalyser\FlightAnalyserFacadeInterface
     */
    public function createFlightAnalyserFacade(): FlightAnalyserFacadeInterface
    {
        return new FlightAnalyserFacade();
    }

    /**
     * @return \App\FlightFileParser\FlightFileParserFacadeInterface
     */
    public function createFlightFileParserFacade(): FlightFileParserFacadeInterface
    {
        return new FlightFileParserFacade();
    }

    /**
     * @return \App\Flight\Domain\Validator\FlightValidatorInterface
     */
    private function createFlightValidator(): FlightValidatorInterface
    {
        return new FlightValidator($this->createAirlineFacade());
    }

    /**
     * @return \App\Airline\AirlineFacadeInterface
     */
    private function createAirlineFacade(): AirlineFacadeInterface
    {
        return new AirlineFacade();
    }

    /**
     * @return \App\Flight\Domain\Mapper\FlightMapperInterface
     */
    private function createFlightMapper(): FlightMapperInterface
    {
        return new FlightMapper();
    }

    /**
     * @return \App\Flight\Domain\Cleaner\FlightTransferCleanerInterface[]
     */
    private function createFlightBuilderTransferCleaners(): array
    {
        return [
            $this->createAirlineNameCleaner(),
        ];
    }

    /**
     * @return \App\Flight\Domain\Cleaner\FlightTransferCleanerInterface
     */
    private function createAirlineNameCleaner(): FlightTransferCleanerInterface
    {
        return new AirlineNameCleaner();
    }
}
