<?php

declare(strict_types=1);

namespace App\Flight\Domain\Builder;

use App\Flight\Domain\Mapper\FlightMapperInterface;
use App\Flight\Domain\Validator\FlightValidatorInterface;
use App\Flight\Transfer\FlightWrapperTransfer;

class FlightBuilder implements FlightBuilderInterface
{
    public function __construct(
        private readonly FlightValidatorInterface $validator,
        private readonly array $flightTransferCleaners,
        private readonly FlightMapperInterface $mapper,
    ) {
    }

    /**
     * @param array $input
     *
     * @return \App\Flight\Transfer\FlightWrapperTransfer
     */
    public function buildFlight(array $input): FlightWrapperTransfer
    {
        $flightEntityWrapper = new FlightWrapperTransfer();
        $flightBuilderTransfer = $this->validator->findErrors($input);
        $flightEntityWrapper->setErrors($flightBuilderTransfer->getErrors());

        if (!$flightBuilderTransfer->isValid()) {
            return $flightEntityWrapper;
        }
        foreach ($this->flightTransferCleaners as $flightTransferCleaner) {
            $flightBuilderTransfer = $flightTransferCleaner->clean($flightBuilderTransfer);
        }

        return $this->mapper->map($flightBuilderTransfer, $flightEntityWrapper);
    }
}
