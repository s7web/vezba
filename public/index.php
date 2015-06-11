<?php
//error_reporting(0);
date_default_timezone_set('UTC');
require_once "../app/init.php";
require_once "../app/core/ErrorHandler.php";

$app = new App();
$app->setRequest($routes);
$app->run();
