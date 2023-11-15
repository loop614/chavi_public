<?php

declare(strict_types=1);

namespace App\Flight\Infrastructure;

use App\Flight\Domain\Entity\FlightCollection;
use App\Flight\FlightFactory;
use App\FlightFileParser\Transfer\ParseResultTransfer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'parse')]
class ParseCommand extends Command
{
    private const SAMPLE_PATH = "./input/input_sample.jsonl";

    /**
     * @var \App\Flight\FlightFactory
     */
    private readonly FlightFactory $factory;

    public function __construct()
    {
        $this->factory = new FlightFactory();

        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = file(self::SAMPLE_PATH);
        if (!$file) {
            $output->write("ERROR: file not found\n");
            return Command::FAILURE;
        }

        $parseResult = $this->factory
            ->createFlightFileParserFacade()
            ->parseFileToCollection($file);

        $this->printFileParseResult($parseResult, $output);

        $analysis = $this->factory
            ->createFlightAnalyserFacade()
            ->analyseFlightCollection($parseResult->getFlightCollection());
        $this->printThreeLongestFlights($analysis->getThreeLongestFlights(), $output);
        $this->printAirlinesMostMissedLandings($analysis->getAirlinesMostMissedLandings(), $output);
        $this->printMostOvernightsPersonOnAirplaneDestinations(
            $analysis->getMostOvernightsPersonOnAirplaneDestinations(),
            $output
        );
        $this->printMostOvernightsAirplaneOnDestinations($analysis->getMostOvernightsAirplaneOnDestinations(), $output);

        return Command::SUCCESS;
    }

    /**
     * @param \App\FlightFileParser\Transfer\ParseResultTransfer $parseResult
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function printFileParseResult(ParseResultTransfer $parseResult, OutputInterface $output): void
    {
        $output->writeln("Parsed well:");
        $output->writeln((string)$parseResult->getFlightsCount());
        $output->writeln("Parsed with errors:");
        $output->writeln((string)$parseResult->countParsedWithError);
        $output->writeln("-------");
    }

    /**
     * @param \App\Flight\Domain\Entity\FlightCollection $flightCollection
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function printThreeLongestFlights(FlightCollection $flightCollection, OutputInterface $output): void
    {
        $output->writeln("Three longest:");
        foreach ($flightCollection->getFlights() as $flight) {
            $output->writeln($flight->toString());
        }
        $output->writeln("-------");
    }

    /**
     * @param string[] $mostMissed
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function printAirlinesMostMissedLandings(array $mostMissed, OutputInterface $output): void
    {
        $output->write("Airlines most missed landings:\n");
        foreach ($mostMissed as $mostMissedAirline) {
            $output->writeln($mostMissedAirline);
        }
        $output->writeln("-------");
    }

    /**
     * @param string[] $mostOvernightStaysDestinations
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function printMostOvernightsPersonOnAirplaneDestinations(
        array $mostOvernightStaysDestinations,
        OutputInterface $output
    ): void {
        if (count($mostOvernightStaysDestinations) === 0) {
            return;
        }
        $output->writeln("Most person in plane overnight destinations:");
        foreach ($mostOvernightStaysDestinations as $mostOvernightStaysDestination) {
            $output->writeln($mostOvernightStaysDestination);
        }
        $output->writeln("-------");
    }

    /**
     * @param string[] $mostOvernightsAirplaneOnDestinations
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function printMostOvernightsAirplaneOnDestinations(
        array $mostOvernightsAirplaneOnDestinations,
        OutputInterface $output
    ): void {
        if (count($mostOvernightsAirplaneOnDestinations) === 0) {
            return;
        }
        $output->writeln("Airplanes spent most of their nights at destinations:");
        foreach ($mostOvernightsAirplaneOnDestinations as $mostOvernightsAirplaneOnDestination) {
            $output->writeln($mostOvernightsAirplaneOnDestination);
        }
        $output->writeln("-------");
    }
}
