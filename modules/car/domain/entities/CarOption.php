<?php

declare(strict_types=1);

namespace app\modules\car\domain\entities;

final class CarOption
{
    private function __construct(
        private readonly ?int $id,
        private readonly ?int $carId,
        private readonly string $brand,
        private readonly string $model,
        private readonly int $year,
        private readonly string $body,
        private readonly int $mileage
    ) {
    }

    public static function create(string $brand, string $model, int $year, string $body, int $mileage): self
    {
        return new self(null, null, $brand, $model, $year, $body, $mileage);
    }

    public static function fromDatabase(
        int $id,
        int $carId,
        string $brand,
        string $model,
        int $year,
        string $body,
        int $mileage
    ): self {
        return new self($id, $carId, $brand, $model, $year, $body, $mileage);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarId(): ?int
    {
        return $this->carId;
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
