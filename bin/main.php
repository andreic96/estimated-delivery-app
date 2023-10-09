<?php

use App\AppManager;

require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../config/container.php';

/** @var AppManager $appManager */
$appManager = $container->get(AppManager::class);
$appManager->run();
