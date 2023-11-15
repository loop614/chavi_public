<?php

declare(strict_types=1);

namespace App\Airline\Transfer;

final class AirlineBuilderTransfer
{
    /**
     * @param ?string $name
     */
    public function __construct(private ?string $name = null)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
