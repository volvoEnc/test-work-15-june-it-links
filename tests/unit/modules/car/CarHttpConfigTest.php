<?php

declare(strict_types=1);

namespace tests\unit\modules\car;

use app\components\ApiErrorHandler;
use app\modules\car\Module;
use app\modules\car\presentation\responses\CarErrorResponseFactory;
use PHPUnit\Framework\TestCase;

final class CarHttpConfigTest extends TestCase
{
    public function testWebConfigRegistersCarModuleAndExactUrlRules(): void
    {
        $config = require __DIR__ . '/../../../../config/web.php';

        self::assertSame(Module::class, $config['modules']['car']['class']);
        self::assertSame(ApiErrorHandler::class, $config['components']['errorHandler']['class']);

        $rules = [];
        foreach ($config['components']['urlManager']['rules'] as $rule) {
            $rules[$rule['pattern']] = $rule;
        }

        self::assertSame('car/car/create', $rules['car/create']['route']);
        self::assertSame('POST', $rules['car/create']['verb']);
        self::assertSame('car/car/list', $rules['car/list']['route']);
        self::assertSame('GET', $rules['car/list']['verb']);
        self::assertSame('car/car/view', $rules['car/<id:\d+>']['route']);
        self::assertSame('GET', $rules['car/<id:\d+>']['verb']);
    }

    public function testErrorFactoryUsesStableJsonShape(): void
    {
        $factory = new CarErrorResponseFactory();

        self::assertSame(['error' => 'Invalid page parameter'], $factory->invalidPage());
        self::assertSame(['error' => 'Car not found'], $factory->notFound());
        self::assertSame(['error' => 'Internal server error'], $factory->internal());
        self::assertSame(
            ['error' => 'Validation failed', 'fields' => ['title' => ['Title cannot be blank.']]],
            $factory->validation(['title' => ['Title cannot be blank.']])
        );
    }
}
