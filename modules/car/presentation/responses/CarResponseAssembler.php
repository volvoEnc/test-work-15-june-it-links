<?php

declare(strict_types=1);

namespace app\modules\car\presentation\responses;

use app\modules\car\domain\entities\Car;

final class CarResponseAssembler
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Car $car): array
    {
        $option = $car->getOption();

        return [
            'id' => $car->getId(),
            'title' => $car->getTitle(),
            'description' => $car->getDescription(),
            'price' => (float) $car->getPrice(),
            'photo_url' => $car->getPhotoUrl(),
            'contacts' => $car->getContacts(),
            'options' => $option === null ? null : [
                'brand' => $option->getBrand(),
                'model' => $option->getModel(),
                'year' => $option->getYear(),
                'body' => $option->getBody(),
                'mileage' => $option->getMileage(),
            ],
        ];
    }

    /**
     * @param array<int, Car> $cars
     * @return array<int, array<string, mixed>>
     */
    public function many(array $cars): array
    {
        return array_map(fn (Car $car): array => $this->toArray($car), $cars);
    }
}
