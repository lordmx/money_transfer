<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Bootstrap.php';

use Symfony\Component\Debug\ErrorHandler;

ini_set('display_errors', 1);
error_reporting(-1);
ErrorHandler::register();

$app = new Silex\Application();
$bootstrap = new Bootstrap($app);
$bootstrap->init(__DIR__, __DIR__ . '/config/config.yml');