<?php

declare(strict_types=1);

namespace app\components;

use Throwable;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\Response;

final class ApiErrorHandler extends ErrorHandler
{
    protected function renderException($exception): void
    {
        error_log((string) $exception);

        if (!Yii::$app->has('response')) {
            parent::renderException($exception);
            return;
        }

        $response = Yii::$app->getResponse();

        if ($response->isSent) {
            return;
        }

        $response->statusCode = $this->statusCode($exception);
        $response->format = Response::FORMAT_JSON;
        $response->data = $this->payload($exception, $response->statusCode);
        $response->send();
    }

    private function statusCode(Throwable $exception): int
    {
        if ($exception instanceof HttpException) {
            return $exception->statusCode;
        }

        return 500;
    }

    /**
     * @return array<string, string>
     */
    private function payload(Throwable $exception, int $statusCode): array
    {
        if ($exception instanceof BadRequestHttpException && str_contains($exception->getMessage(), 'Invalid JSON')) {
            return ['error' => 'Invalid JSON'];
        }

        return match ($statusCode) {
            400 => ['error' => $exception->getMessage() ?: 'Bad request'],
            404 => ['error' => 'Not found'],
            default => ['error' => 'Internal server error'],
        };
    }
}
