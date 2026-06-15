<?php

declare(strict_types=1);

namespace app\modules\car\domain\repositories;

use app\modules\car\application\dto\PaginatedResult;
use app\modules\car\domain\entities\Car;

interface CarRepositoryInterface
{
    public function create(Car $car): Car;

    public function findById(int $id): ?Car;

    public function findPage(int $page, int $perPage): PaginatedResult;
}
