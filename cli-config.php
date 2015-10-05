<?php
require __DIR__ . '/vendor/autoload.php';

$app = new \S7D\Vendor\Routing\Application(__DIR__);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app->em);
