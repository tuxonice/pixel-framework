<?php
require(__DIR__ . '/../configs/global.php');
require __DIR__ . '/../vendor/autoload.php';

// Load settings
$configFile =  __DIR__ . '/../configs/settings.yml';


//Initialize application core
$app = Tlab\AppBoot::create($configFile);
$app->run();
