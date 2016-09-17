<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Bootstrap.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$bootstrap = new app\Bootstrap($app);
$bootstrap->init(__DIR__ . '/config/config.yml');
$di = $bootstrap->getContainer();

$oauth2 = new oauth2\Server();

$oauth2->registerGrant('bearer', new oauth2\grants\BearerGrant(
	$di->get('sessionRepository'),
	$di->get('userRepository')
));

$api = new api\Server($app, $oauth2, Request::createFromGlobals());

$api->registerHandler(new api\handlers\TransferHandler($di));
$api->registerHandler(new api\handlers\UserBalanceHandler($di));
$api->registerHandler(new api\handlers\UserHistoryHandler($di));

$api->init();
$app->run();