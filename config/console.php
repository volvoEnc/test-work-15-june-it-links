<?php

return [
    'id' => 'car-api-console',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'controllerNamespace' => 'app\commands',
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'log' => [
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => require __DIR__ . '/di.php',
    ],
];
