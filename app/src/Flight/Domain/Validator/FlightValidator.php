<?php

declare(strict_types=1);

namespace App\Flight\Domain\Validator;

use App\Airline\AirlineFacadeInterface;
use App\Airline\Domain\AirlineLookup\Exception\AirlineNameNotFoundException;
use App\Airline\Transfer\AirlineBuilderTransfer;
use App\Airplane\Transfer\AirplaneBuilderTransfer;
use App\Flight\Transfer\FlightBuilderTransfer;
use DateTimeImmutable;
use Exception;
use RuntimeException;

class FlightValidator implements FlightValidatorInterface
{
    private const FIELD_TO = "to";
    private const FIELD_FROM = "from";
    private const FIELD_REGISTRATION = "registration";
    private const FIELD_SCHEDULED_START = "scheduled_start";
    private const FIELD_SCHEDULED_END = "scheduled_end";
    private const FIELD_ACTUAL_START = "actual_start";
    private const FIELD_ACTUAL_END = "actual_end";
    private const DESTINATION_BAD_FORMAT_MESSAGE = <<<EOT
        Destination Field '%s' is not in expected format. Three upper case letters expected
        EOT;

    private const REGISTRATION_BAD_FORMAT_MESSAGE = <<<EOT
        Registration Field is not in expected format. Expected Two numbers - Three upper case letters
        EOT;

    private const MANDATORY_FIELD_NOT_SET_MESSAGE = "Mandatory field '%s' is not set";
    private const EXPECTED_STRING_MESSAGE = "Field %s is of type %s expected string";
    private const DATE_BAD_FORMAT_MESSAGE = "Date Field %s is not in expected format and cant be parsed to Datetime";
    private const AIRLINE_LOOKUP_FAILED_MESSAGE = "Airline with %s code could not be found";
    private const AIRLINE_LOOKUP_CRITICAL_FAILED_MESSAGE = "Unexpected error with airline lookup for %s code";

    public function __construct(private readonly AirlineFacadeInterface $airlineFacade)
    {
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    public function findErrors(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer = new FlightBuilderTransfer()
    ): FlightBuilderTransfer {
        $flightBuilderTransfer = $this->validateFieldRegistration($input, $flightBuilderTransfer);
        $flightBuilderTransfer = $this->validateFieldFrom($input, $flightBuilderTransfer);
        $flightBuilderTransfer = $this->validateFieldTo($input, $flightBuilderTransfer);
        $flightBuilderTransfer = $this->validateFieldScheduledStart($input, $flightBuilderTransfer);
        $flightBuilderTransfer = $this->validateFieldScheduledEnd($input, $flightBuilderTransfer);
        $flightBuilderTransfer = $this->validateFieldActualStart($input, $flightBuilderTransfer);
        $flightBuilderTransfer = $this->validateFieldActualEnd($input, $flightBuilderTransfer);

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldTo(array $input, FlightBuilderTransfer $flightBuilderTransfer): FlightBuilderTransfer
    {
        if (!$this->isSetAndString($input, self::FIELD_TO, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }
        $isDestinationValid = $this->validateDestination($input, self::FIELD_TO, $flightBuilderTransfer);
        if ($isDestinationValid) {
            $flightBuilderTransfer->setTo($input[self::FIELD_TO]);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldFrom(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): FlightBuilderTransfer {
        if (!$this->isSetAndString($input, self::FIELD_FROM, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }
        $isDestinationValid = $this->validateDestination($input, self::FIELD_FROM, $flightBuilderTransfer);
        if ($isDestinationValid) {
            $flightBuilderTransfer->setFrom($input[self::FIELD_FROM]);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldRegistration(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): FlightBuilderTransfer {
        if (!$this->isSetAndString($input, self::FIELD_REGISTRATION, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }

        $airlineName = $this->validateRegistration($input, $flightBuilderTransfer);
        if ($airlineName !== null) {
            $airplaneBuilderTransfer = $this->createAirplaneBuilderTransfer(
                $input[self::FIELD_REGISTRATION],
                $airlineName
            );
            $flightBuilderTransfer->setAirplaneBuilderTransfer($airplaneBuilderTransfer);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldScheduledStart(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): FlightBuilderTransfer {
        if (!$this->isSetAndString($input, self::FIELD_SCHEDULED_START, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }

        $dateTimeField = $this->validateDate($input, self::FIELD_SCHEDULED_START, $flightBuilderTransfer);
        if ($dateTimeField !== null) {
            $flightBuilderTransfer->setScheduledStart($dateTimeField);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldScheduledEnd(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): FlightBuilderTransfer {
        if (!$this->isSetAndString($input, self::FIELD_SCHEDULED_END, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }

        $dateTimeField = $this->validateDate($input, self::FIELD_SCHEDULED_END, $flightBuilderTransfer);
        if ($dateTimeField !== null) {
            $flightBuilderTransfer->setScheduledEnd($dateTimeField);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldActualStart(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): FlightBuilderTransfer {
        if (!$this->isSetAndString($input, self::FIELD_ACTUAL_START, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }

        $dateTimeField = $this->validateDate($input, self::FIELD_ACTUAL_START, $flightBuilderTransfer);
        if ($dateTimeField !== null) {
            $flightBuilderTransfer->setActualStart($dateTimeField);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \App\Flight\Transfer\FlightBuilderTransfer
     */
    private function validateFieldActualEnd(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): FlightBuilderTransfer {
        if (!$this->isSetAndString($input, self::FIELD_ACTUAL_END, $flightBuilderTransfer)) {
            return $flightBuilderTransfer;
        }

        $dateTimeField = $this->validateDate($input, self::FIELD_ACTUAL_END, $flightBuilderTransfer);
        if ($dateTimeField !== null) {
            $flightBuilderTransfer->setActualEnd($dateTimeField);
        }

        return $flightBuilderTransfer;
    }

    /**
     * @param array $input
     * @param string $mandatoryField
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return bool
     */
    private function isSetAndString(
        array $input,
        string $mandatoryField,
        FlightBuilderTransfer $flightBuilderTransfer
    ): bool {
        if (!isset($input[$mandatoryField])) {
            $flightBuilderTransfer->addError(sprintf(self::MANDATORY_FIELD_NOT_SET_MESSAGE, $mandatoryField));

            return false;
        }
        if (!is_string($input[$mandatoryField])) {
            $flightBuilderTransfer->addError(sprintf(
                self::EXPECTED_STRING_MESSAGE,
                $mandatoryField,
                gettype($input[$mandatoryField])
            ));

            return false;
        }

        return true;
    }

    /**
     * @param array $input
     * @param string $destinationField
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return bool
     */
    private function validateDestination(
        array $input,
        string $destinationField,
        FlightBuilderTransfer $flightBuilderTransfer
    ): bool {
        if (!$this->isThreeUpperCaseLetters($input[$destinationField])) {
            $flightBuilderTransfer->addError(sprintf(self::DESTINATION_BAD_FORMAT_MESSAGE, $destinationField));

            return false;
        }

        return true;
    }

    /**
     * @param array $input
     * @param string $mandatoryField
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return \DateTimeImmutable|bool
     */
    private function validateDate(
        array $input,
        string $mandatoryField,
        FlightBuilderTransfer $flightBuilderTransfer
    ): ?DateTimeImmutable {
        $dateTimeField = $this->convertStringToDateTimeImmutable($input[$mandatoryField]);
        if ($dateTimeField === null) {
            $flightBuilderTransfer->addError(sprintf(self::DATE_BAD_FORMAT_MESSAGE, $mandatoryField));
            return null;
        }

        return $dateTimeField;
    }

    /**
     * @param array $input
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return string|null
     */
    private function validateRegistration(
        array $input,
        FlightBuilderTransfer $flightBuilderTransfer
    ): ?string {
        $validFormat = $this->validateRegistrationFormat($input[self::FIELD_REGISTRATION], $flightBuilderTransfer);
        if (!$validFormat) {
            return null;
        }

        try {
            return $this->airlineFacade->findAirlineNameByRegistration($input[self::FIELD_REGISTRATION]);
        } catch (AirlineNameNotFoundException $e) {
            $flightBuilderTransfer->addError(
                sprintf(self::AIRLINE_LOOKUP_FAILED_MESSAGE, $input[self::FIELD_REGISTRATION])
            );

            return null;
        } catch (Exception $e) {
            $flightBuilderTransfer->addError(sprintf(
                self::AIRLINE_LOOKUP_CRITICAL_FAILED_MESSAGE,
                $input[self::FIELD_REGISTRATION]
            ));

            return null;
        }
    }

    /**
     * @param string $destination
     * @param \App\Flight\Transfer\FlightBuilderTransfer $flightBuilderTransfer
     *
     * @return bool
     */
    private function validateRegistrationFormat(
        string $destination,
        FlightBuilderTransfer $flightBuilderTransfer
    ): bool {
        if (!str_contains($destination, '-')) {
            $flightBuilderTransfer->addError(self::REGISTRATION_BAD_FORMAT_MESSAGE);
            return false;
        }

        $parts = explode('-', $destination);
        if (count($parts) !== 2) {
            $flightBuilderTransfer->addError(self::REGISTRATION_BAD_FORMAT_MESSAGE);
            return false;
        }

        if (!$this->isUpperCaseLetter($parts[0][0])) {
            $flightBuilderTransfer->addError(self::REGISTRATION_BAD_FORMAT_MESSAGE);
            return false;
        }

        if (isset($parts[0][1]) && !$this->isUpperCaseLetter($parts[0][1])) {
            $flightBuilderTransfer->addError(self::REGISTRATION_BAD_FORMAT_MESSAGE);
            return false;
        }

        if (!$this->isThreeUpperCaseLetters($parts[1])) {
            $flightBuilderTransfer->addError(self::REGISTRATION_BAD_FORMAT_MESSAGE);
            return false;
        }

        return true;
    }

    /**
     * @param string $registration
     * @param string $airlineName
     *
     * @return \App\Airplane\Transfer\AirplaneBuilderTransfer
     */
    private function createAirplaneBuilderTransfer(string $registration, string $airlineName): AirplaneBuilderTransfer
    {
        $airlineBuilderTransfer = $this->createAirlineBuilderTransfer($airlineName);
        $airplaneBuilderTransfer = new AirplaneBuilderTransfer();
        $airplaneBuilderTransfer->setRegistration($registration);
        $airplaneBuilderTransfer->setAirlineBuilderTransfer($airlineBuilderTransfer);

        return $airplaneBuilderTransfer;
    }

    /**
     * @param string $airlineName
     *
     * @return \App\Airline\Transfer\AirlineBuilderTransfer
     */
    private function createAirlineBuilderTransfer(string $airlineName): AirlineBuilderTransfer
    {
        $airLineBuilderTransfer = new AirlineBuilderTransfer();
        $airLineBuilderTransfer->setName($airlineName);

        return $airLineBuilderTransfer;
    }

    /**
     * @param string $input
     *
     * @return DateTimeImmutable|null
     */
    private function convertStringToDateTimeImmutable(string $input): ?DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($input);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param string $input
     *
     * @return bool
     */
    private function isThreeUpperCaseLetters(string $input): bool
    {
        if (strlen($input) !== 3) {
            return false;
        }

        foreach ([0, 1, 2] as $i) {
            if (!$this->isUpperCaseLetter($input[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $input
     *
     * @return bool
     */
    private function isUpperCaseLetter(string $input): bool
    {
        return $input <= "Z" && $input >= "A";
    }
}
