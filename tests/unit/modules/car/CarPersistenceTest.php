<?php

declare(strict_types=1);

namespace tests\unit\modules\car;

use app\modules\car\domain\entities\Car;
use app\modules\car\domain\entities\CarOption;
use app\modules\car\infrastructure\mappers\CarDataMapper;
use app\modules\car\infrastructure\repositories\PostgresCarRepository;
use PHPUnit\Framework\TestCase;
use Yii;

final class CarPersistenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Yii::$app->db->createCommand()->delete('{{%car_option}}')->execute();
        Yii::$app->db->createCommand()->delete('{{%car}}')->execute();
    }

    public function testMapperBuildsCarWithOptionsFromJoinedRow(): void
    {
        $mapper = new CarDataMapper();

        $car = $mapper->toEntity([
            'id' => 7,
            'title' => 'Toyota Camry 2020',
            'description' => 'Good condition',
            'price' => '1500000.00',
            'photo_url' => 'https://example.com/camry.jpg',
            'contacts' => '+7 999 000-00-00',
            'created_at' => '2026-06-15 10:00:00',
            'option_id' => 9,
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year' => 2020,
            'body' => 'sedan',
            'mileage' => 45000,
        ]);

        self::assertSame(7, $car->getId());
        self::assertSame('1500000.00', $car->getPrice());
        self::assertNotNull($car->getOption());
        self::assertSame('Toyota', $car->getOption()->getBrand());
    }

    public function testRepositoryPersistsAndReadsCarWithoutOptions(): void
    {
        $repository = new PostgresCarRepository(Yii::$app->db, new CarDataMapper());
        $car = Car::create(
            'Toyota Camry',
            'Good condition',
            '1500000.00',
            'https://example.com/camry.jpg',
            '+7 999 000-00-00',
            null
        );

        $created = $repository->create($car);
        $loaded = $repository->findById($created->getId());

        self::assertNotNull($loaded);
        self::assertSame('Toyota Camry', $loaded->getTitle());
        self::assertNull($loaded->getOption());
    }

    public function testRepositoryPersistsAndReadsCarWithOptions(): void
    {
        $repository = new PostgresCarRepository(Yii::$app->db, new CarDataMapper());
        $car = Car::create(
            'BMW 320i',
            'Clean car',
            '2200000.00',
            'https://example.com/bmw.jpg',
            '+7 999 111-22-33',
            CarOption::create('BMW', '320i', 2021, 'sedan', 30000)
        );

        $created = $repository->create($car);
        $loaded = $repository->findById($created->getId());

        self::assertNotNull($loaded);
        self::assertNotNull($loaded->getOption());
        self::assertSame('BMW', $loaded->getOption()->getBrand());
        self::assertSame(30000, $loaded->getOption()->getMileage());
    }

    public function testRepositoryReturnsPaginatedCarsInNewestOrder(): void
    {
        $repository = new PostgresCarRepository(Yii::$app->db, new CarDataMapper());

        $first = $repository->create(Car::create(
            'First',
            'Oldest',
            '100.00',
            'https://example.com/first.jpg',
            '+7 999 000-00-01',
            null
        ));
        $second = $repository->create(Car::create(
            'Second',
            'Newest',
            '200.00',
            'https://example.com/second.jpg',
            '+7 999 000-00-02',
            null
        ));

        $page = $repository->findPage(1, 20);

        self::assertSame(2, $page->getTotal());
        self::assertSame($second->getId(), $page->getItems()[0]->getId());
        self::assertSame($first->getId(), $page->getItems()[1]->getId());
    }
}
