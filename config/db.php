<?php

return [
    'class' => yii\db\Connection::class,
    'dsn' => getenv('DB_DSN') ?: 'pgsql:host=db;port=5432;dbname=car_api',
    'username' => getenv('DB_USERNAME') ?: 'postgres',
    'password' => getenv('DB_PASSWORD') ?: 'postgres',
    'charset' => 'utf8',
];
