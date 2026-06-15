<?php

$config = [
    'id' => 'car-api',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'components' => [
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
    'container' => [
        'definitions' => require __DIR__ . '/di.php',
    ],
];

return $config;
