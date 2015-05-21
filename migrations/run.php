<?php

use Nextras\Migrations\Bridges;
use Nextras\Migrations\Controllers;
use Nextras\Migrations\Drivers;
use Nextras\Migrations\Extensions;

/** @var callable $containerFactory */
$containerFactory = require __DIR__ . '/../app/bootstrap.php';

$params = [
    'appDir' => __DIR__ . '/../app',
    'wwwDir' => __DIR__ . '/../www',
];

/** @var \Nette\DI\Container $container */
$container = $containerFactory([], $params);

/** @var \Nette\Database\Connection $connection */
$connection = $container->getByType('Nette\Database\Connection');

$dbal = new Bridges\NetteDatabase\NetteAdapter($connection);
$driver = new Drivers\MySqlDriver($dbal);
$controller = new Controllers\ConsoleController($driver);

$baseDir = __DIR__;
$controller->addGroup('structures', "$baseDir/structures");
$controller->addGroup('basic-data', "$baseDir/basic-data", ['structures']);
$controller->addGroup('dummy-data', "$baseDir/dummy-data", ['basic-data']);
$controller->addExtension('sql', new Extensions\SqlHandler($driver));

$controller->run();
