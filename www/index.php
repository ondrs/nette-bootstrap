<?php

// Uncomment this line if you must temporarily take down your site for maintenance.
// require '.maintenance.php';

/** @var callable $containerFactory */
$containerFactory = require __DIR__ . '/../app/bootstrap.php';

/** @var \Nette\DI\Container $container */
$container = $containerFactory();

$container->getService('application')->run();
