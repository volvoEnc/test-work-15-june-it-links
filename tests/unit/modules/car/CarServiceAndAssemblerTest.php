<?php

declare(strict_types=1);

namespace tests\unit\modules\car;

use app\modules\car\application\commands\CreateCarCommand;
use app\modules\car\application\commands\CreateCarOptionCommand;
use app\modules\car\application\services\CarService;
use app\modules\car\domain\exceptions\CarNotFoundException;
use app\modules\car\infrastructure\mappers\CarDataMapper;
use app\modules\car\infrastructure\repositories\PostgresCarRepository;
use app\modules\car\presentation\responses\CarResponseAssembler;
use PHPUnit\Framework\TestCase;
use Throwable;
use Yii;

final class CarServiceAndAssemblerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Yii::$app->db->createCommand()->delete('{{%car_option}}')->execute();
        Yii::$app->db->createCommand()->delete('{{%car}}')->execute();
    }

    public function testServiceCreatesCarAndAssemblerReturnsApiShape(): void
    {
        $service = $this->service();
        $assembler = new CarResponseAssembler();

        $car = $service->create(new CreateCarCommand(
            'BMW 320i',
            'Clean car',
            '2200000.00',
            'https://example.com/bmw.jpg',
            '+7 999 111-22-33',
            new CreateCarOptionCommand('BMW', '320i', 2021, 'sedan', 30000)
        ));

        self::assertSame([
            'id' => $car->getId(),
            'title' => 'BMW 320i',
            'description' => 'Clean car',
            'price' => 2200000.0,
            'photo_url' => 'https://example.com/bmw.jpg',
            'contacts' => '+7 999 111-22-33',
            'options' => [
                'brand' => 'BMW',
                'model' => '320i',
                'year' => 2021,
                'body' => 'sedan',
                'mileage' => 30000,
            ],
        ], $assembler->toArray($car));
    }

    public function testServiceRollsBackCarWhenOptionPersistenceFails(): void
    {
        $service = $this->service();

        try {
            $service->create(new CreateCarCommand(
                'Invalid',
                'Invalid option year',
                '100.00',
                'https://example.com/invalid.jpg',
                '+7 999 000-00-00',
                new CreateCarOptionCommand('Ford', 'T', 2201, 'sedan', 1)
            ));
            self::fail('Expected DB constraint failure.');
        } catch (Throwable) {
            self::assertSame(0, (int) Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{%car}}')->queryScalar());
        }
    }

    public function testServiceThrowsNotFoundForMissingCar(): void
    {
        $this->expectException(CarNotFoundException::class);

        $this->service()->getById(999999);
    }

    private function service(): CarService
    {
        return new CarService(
            new PostgresCarRepository(Yii::$app->db, new CarDataMapper()),
            Yii::$app->db
        );
    }
}
