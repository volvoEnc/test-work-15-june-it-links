<?php

$config = [
    'id' => 'car-api',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'modules' => [
        'car' => [
            'class' => app\modules\car\Module::class,
        ],
    ],
    'components' => [
        'errorHandler' => [
            'class' => app\components\ApiErrorHandler::class,
        ],
        'request' => [
            'cookieValidationKey' => getenv('YII_COOKIE_VALIDATION_KEY') ?: 'local-dev-cookie-validation-key',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'db' => require __DIR__ . '/db.php',
        'log' => [
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => yii\web\UrlRule::class, 'pattern' => 'car/create', 'route' => 'car/car/create', 'verb' => 'POST'],
                ['class' => yii\web\UrlRule::class, 'pattern' => 'car/list', 'route' => 'car/car/list', 'verb' => 'GET'],
                ['class' => yii\web\UrlRule::class, 'pattern' => 'car/<id:\d+>', 'route' => 'car/car/view', 'verb' => 'GET'],
            ],
        ],
    ],
    'container' => [
        'definitions' => require __DIR__ . '/di.php',
    ],
];

return $config;
