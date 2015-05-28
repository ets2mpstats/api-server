<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('ApiServer', __DIR__.'/../src/');

use \ApiServer\ApiServer;

$app = new ApiServer();
