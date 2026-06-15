<?php

use app\modules\car\application\services\CarService;
use app\modules\car\domain\repositories\CarRepositoryInterface;
use app\modules\car\infrastructure\mappers\CarDataMapper;
use app\modules\car\infrastructure\repositories\PostgresCarRepository;

return [
    CarDataMapper::class => CarDataMapper::class,
    CarRepositoryInterface::class => static fn (): PostgresCarRepository => new PostgresCarRepository(
        Yii::$app->db,
        new CarDataMapper()
    ),
    CarService::class => static fn ($container): CarService => new CarService(
        $container->get(CarRepositoryInterface::class),
        Yii::$app->db
    ),
];
