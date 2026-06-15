<?php

declare(strict_types=1);

namespace app\modules\car\infrastructure\mappers;

use app\modules\car\domain\entities\Car;
use app\modules\car\domain\entities\CarOption;

final class CarDataMapper
{
    /**
     * @param array<string, mixed> $row
     */
    public function toEntity(array $row): Car
    {
        $option = null;

        if (!empty($row['option_id'])) {
            $option = CarOption::fromDatabase(
                (int) $row['option_id'],
                (int) ($row['option_car_id'] ?? $row['id']),
                (string) $row['brand'],
                (string) $row['model'],
                (int) $row['year'],
                (string) $row['body'],
                (int) $row['mileage']
            );
        }

        return Car::fromDatabase(
            (int) $row['id'],
            (string) $row['title'],
            (string) $row['description'],
            $this->normalizePrice((string) $row['price']),
            (string) $row['photo_url'],
            (string) $row['contacts'],
            (string) $row['created_at'],
            $option
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toCarInsertRow(Car $car): array
    {
        return [
            'title' => $car->getTitle(),
            'description' => $car->getDescription(),
            'price' => $car->getPrice(),
            'photo_url' => $car->getPhotoUrl(),
            'contacts' => $car->getContacts(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toOptionInsertRow(int $carId, CarOption $option): array
    {
        return [
            'car_id' => $carId,
            'brand' => $option->getBrand(),
            'model' => $option->getModel(),
            'year' => $option->getYear(),
            'body' => $option->getBody(),
            'mileage' => $option->getMileage(),
        ];
    }

    private function normalizePrice(string $price): string
    {
        return number_format((float) $price, 2, '.', '');
    }
}
