<?php

declare(strict_types=1);

namespace app\modules\car\presentation\responses;

final class CarErrorResponseFactory
{
    /**
     * @param array<string, array<int, string>> $fields
     * @return array<string, mixed>
     */
    public function validation(array $fields): array
    {
        return [
            'error' => 'Validation failed',
            'fields' => $fields,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function invalidPage(): array
    {
        return ['error' => 'Invalid page parameter'];
    }

    /**
     * @return array<string, string>
     */
    public function notFound(): array
    {
        return ['error' => 'Car not found'];
    }

    /**
     * @return array<string, string>
     */
    public function internal(): array
    {
        return ['error' => 'Internal server error'];
    }
}
