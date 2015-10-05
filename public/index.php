<?php
require_once '../vendor/autoload.php';
use S7D\Vendor\Routing\Application;

$app = new Application(__DIR__ . '/..');
$app->run();
