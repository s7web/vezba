<?php
date_default_timezone_set('UTC');
require_once '../vendor/autoload.php';
use S7D\Core\Routing\Application;

$app = new Application(__DIR__ . '/../');
$app->run();
