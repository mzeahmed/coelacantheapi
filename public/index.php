<?php

use App\Bootstrap;

define('ROOT_PATH', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';

Bootstrap::getInstance();
