<?php
require __DIR__ . '/vendor/autoload.php';

$app = new \S7D\Core\Routing\Application(__DIR__);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app->em);
