<?php

require_once __DIR__ . '/vendor/autoload.php';

use \Phpmig\Adapter;

$app = new Silex\Application();
$config = __DIR__ . '/config/config.yml';
$app->register(new \Euskadi31\Silex\Provider\ConfigServiceProvider($config));
$container = new ArrayObject();

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

// replace this with a better Phpmig\Adapter\AdapterInterface
$container['phpmig.adapter'] = new Adapter\File\Flat(__DIR__ . DIRECTORY_SEPARATOR . 'migrations/.migrations.log');

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

// You can also provide an array of migration files
// $container['phpmig.migrations'] = array_merge(
//     glob('migrations_1/*.php'),
//     glob('migrations_2/*.php')
// );

$container['db'] = $app['db'];

return $container;