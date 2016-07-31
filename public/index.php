<?php
require __DIR__ . '/../vendor/autoload.php';

// Load settings
$settings = require __DIR__ . '/../configs/settings.php';


//Initialize application core
$app = Tlab\AppBoot::create($settings);
$app->run();
