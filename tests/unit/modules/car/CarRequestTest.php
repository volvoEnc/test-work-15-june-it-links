<?php

declare(strict_types=1);

namespace tests\unit\modules\car;

use app\modules\car\presentation\requests\CarCreateRequest;
use app\modules\car\presentation\requests\CarListRequest;
use PHPUnit\Framework\TestCase;

final class CarRequestTest extends TestCase
{
    public function testCreateRequestAcceptsMissingOptions(): void
    {
        $request = new CarCreateRequest();
        $request->load($this->validPayload(), '');

        self::assertTrue($request->validate());
        self::assertNull($request->toCommand()->getOption());
    }

    public function testCreateRequestAcceptsNullOptions(): void
    {
        $payload = $this->validPayload();
        $payload['options'] = null;

        $request = new CarCreateRequest();
        $request->load($payload, '');

        self::assertTrue($request->validate());
        self::assertNull($request->toCommand()->getOption());
    }

    public function testCreateRequestRejectsPartialOptionsWithNestedFieldErrors(): void
    {
        $payload = $this->validPayload();
        $payload['options'] = ['brand' => 'Toyota'];

        $request = new CarCreateRequest();
        $request->load($payload, '');

        self::assertFalse($request->validate());
        self::assertArrayHasKey('options.model', $request->getErrors());
        self::assertArrayHasKey('options.year', $request->getErrors());
        self::assertArrayHasKey('options.body', $request->getErrors());
        self::assertArrayHasKey('options.mileage', $request->getErrors());
    }

    public function testCreateRequestRejectsInvalidScalars(): void
    {
        $payload = $this->validPayload();
        $payload['title'] = '';
        $payload['price'] = -1;
        $payload['photo_url'] = str_repeat('a', 2049);

        $request = new CarCreateRequest();
        $request->load($payload, '');

        self::assertFalse($request->validate());
        self::assertArrayHasKey('title', $request->getErrors());
        self::assertArrayHasKey('price', $request->getErrors());
        self::assertArrayHasKey('photo_url', $request->getErrors());
    }

    public function testListRequestRejectsInvalidPage(): void
    {
        $request = new CarListRequest();
        $request->load(['page' => 'abc'], '');

        self::assertFalse($request->validate());

        $request = new CarListRequest();
        $request->load(['page' => 0], '');

        self::assertFalse($request->validate());
    }

    public function testListRequestDefaultsToFirstPage(): void
    {
        $request = new CarListRequest();
        $request->load([], '');

        self::assertTrue($request->validate());
        self::assertSame(1, $request->getPage());
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'title' => 'Toyota Camry',
            'description' => 'Good condition',
            'price' => 1500000,
            'photo_url' => 'https://example.com/camry.jpg',
            'contacts' => '+7 999 000-00-00',
        ];
    }
}
