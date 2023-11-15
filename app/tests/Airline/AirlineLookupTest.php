<?php

declare(strict_types=1);

namespace App\Tests\Airline;

use App\Airline\AirlineFacade;
use App\Airline\AirlineFacadeInterface;
use App\Airline\Domain\AirlineLookup\Exception\AirlineNameNotFoundException;
use PHPUnit\Framework\TestCase;

class AirlineLookupTest extends TestCase
{
    /**
     * @var \App\Airline\AirlineFacadeInterface
     */
    private readonly AirlineFacadeInterface $sut;

    public function __construct()
    {
        $this->sut = new AirlineFacade();

        parent::__construct();
    }

    /**
     * @return array [][]
     */
    private function provider(): array
    {
        return [
            ['HA-AAA', 'Alpha Airlines'],
            ['HA-AAB', 'Alpha Airlines'],
            ['HA-AAC', 'Alpha Airline'],

            ['D-AAA', 'Delta Freight'],
            ['D-AAB', 'Delta Freight'],
            ['D-AAC', 'Delta Freight'],

            ['OO-AAA', 'Oscar Air'],
            ['OO-AAB', 'Oscar Air'],
            ['OO-AAC', 'Oscar Air'],
        ];
    }

    /**
     * @return void
     */
    public function testLookupThrowsExceptionWhenNoMapping(): void
    {
        $this->expectException(AirlineNameNotFoundException::class);

        $this->sut->findAirlineNameByRegistration('unknown');
    }

    /**
     * @return void
     */
    public function testLookupFromRegistration(): void
    {
        foreach ($this->provider() as $provider) {
            $this->assertSame($provider[1], $this->sut->findAirlineNameByRegistration($provider[0]));
        }
    }
}
