<?php

function getDb()
{
    $config = __DIR__ . '/../config/config.yml';
    $app = new Silex\Application();
    $app->register(new \Euskadi31\Silex\Provider\ConfigServiceProvider($config));

    $app->register(new \Silex\Provider\DoctrineServiceProvider(), [
        'db.options' => [
            'driver'    => 'pdo_mysql',
            'host'      => $app['mysql']['host'],
            'dbname'    => $app['mysql']['schema'],
            'user'      => $app['mysql']['user'],
            'password'  => $app['mysql']['password'],
            'charset'   => 'utf8',
        ],
    ]);

    return $app['db'];
}