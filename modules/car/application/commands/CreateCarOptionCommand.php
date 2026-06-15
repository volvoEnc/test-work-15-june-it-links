<?php

declare(strict_types=1);

namespace app\modules\car\application\commands;

final class CreateCarOptionCommand
{
    public function __construct(
        private readonly string $brand,
        private readonly string $model,
        private readonly int $year,
        private readonly string $body,
        private readonly int $mileage
    ) {
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getMileage(): int
    {
        return $this->mileage;
    }
}
