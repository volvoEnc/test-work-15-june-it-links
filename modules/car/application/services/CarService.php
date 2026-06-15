<?php

declare(strict_types=1);

namespace app\modules\car\application\services;

use app\modules\car\application\commands\CreateCarCommand;
use app\modules\car\application\dto\PaginatedResult;
use app\modules\car\domain\entities\Car;
use app\modules\car\domain\entities\CarOption;
use app\modules\car\domain\exceptions\CarNotFoundException;
use app\modules\car\domain\repositories\CarRepositoryInterface;
use yii\db\Connection;

final class CarService
{
    public function __construct(
        private readonly CarRepositoryInterface $cars,
        private readonly Connection $db
    ) {
    }

    public function create(CreateCarCommand $command): Car
    {
        $optionCommand = $command->getOption();
        $option = $optionCommand === null
            ? null
            : CarOption::create(
                $optionCommand->getBrand(),
                $optionCommand->getModel(),
                $optionCommand->getYear(),
                $optionCommand->getBody(),
                $optionCommand->getMileage()
            );

        $car = Car::create(
            $command->getTitle(),
            $command->getDescription(),
            $command->getPrice(),
            $command->getPhotoUrl(),
            $command->getContacts(),
            $option
        );

        return $this->db->transaction(fn (): Car => $this->cars->create($car));
    }

    public function getById(int $id): Car
    {
        $car = $this->cars->findById($id);

        if ($car === null) {
            throw new CarNotFoundException('Car not found.');
        }

        return $car;
    }

    public function list(int $page, int $perPage): PaginatedResult
    {
        return $this->cars->findPage($page, $perPage);
    }
}
