<?php
require "Database.php";
require "BaseModel.php";
require "Container.php";
require "Controller.php";
require "Application.php";

$app = new Application();
$app->run();