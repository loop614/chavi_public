<?php

declare(strict_types=1);

namespace App\Airline\Domain\Entity;

final class Airline
{
    /**
     * @param string $name
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
