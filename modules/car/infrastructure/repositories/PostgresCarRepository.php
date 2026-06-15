<?php

declare(strict_types=1);

namespace app\modules\car\infrastructure\repositories;

use app\modules\car\application\dto\PaginatedResult;
use app\modules\car\domain\entities\Car;
use app\modules\car\domain\repositories\CarRepositoryInterface;
use app\modules\car\infrastructure\mappers\CarDataMapper;
use yii\db\Connection;

final class PostgresCarRepository implements CarRepositoryInterface
{
    public function __construct(
        private readonly Connection $db,
        private readonly CarDataMapper $mapper
    ) {
    }

    public function create(Car $car): Car
    {
        $row = $this->db->createCommand(
            'INSERT INTO {{%car}} (title, description, price, photo_url, contacts)
             VALUES (:title, :description, :price, :photo_url, :contacts)
             RETURNING id'
        )
            ->bindValues($this->mapper->toCarInsertRow($car))
            ->queryOne();

        $carId = (int) $row['id'];

        if ($car->getOption() !== null) {
            $this->db->createCommand()->insert(
                '{{%car_option}}',
                $this->mapper->toOptionInsertRow($carId, $car->getOption())
            )->execute();
        }

        return $this->findById($carId);
    }

    public function findById(int $id): ?Car
    {
        $row = $this->baseQuery()
            ->where(['c.id' => $id])
            ->one($this->db);

        if ($row === false) {
            return null;
        }

        return $this->mapper->toEntity($row);
    }

    public function findPage(int $page, int $perPage): PaginatedResult
    {
        $offset = ($page - 1) * $perPage;
        $total = (int) $this->db->createCommand('SELECT COUNT(*) FROM {{%car}}')->queryScalar();
        $rows = $this->baseQuery()
            ->orderBy(['c.created_at' => SORT_DESC, 'c.id' => SORT_DESC])
            ->limit($perPage)
            ->offset($offset)
            ->all($this->db);

        return new PaginatedResult(
            array_map(fn (array $row): Car => $this->mapper->toEntity($row), $rows),
            $page,
            $perPage,
            $total
        );
    }

    private function baseQuery(): \yii\db\Query
    {
        return (new \yii\db\Query())
            ->select([
                'c.id',
                'c.title',
                'c.description',
                'c.price',
                'c.photo_url',
                'c.contacts',
                'c.created_at',
                'option_id' => 'o.id',
                'option_car_id' => 'o.car_id',
                'o.brand',
                'o.model',
                'o.year',
                'o.body',
                'o.mileage',
            ])
            ->from(['c' => '{{%car}}'])
            ->leftJoin(['o' => '{{%car_option}}'], 'o.car_id = c.id');
    }
}
