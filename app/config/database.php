<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    "driver"   => DATABASE_DRIVER,
    "host"     => DATABASE_HOST,
    "username" => DATABASE_USERNAME,
    "password" => DATABASE_PASSWORD,
    "database" => DATABASE_NAME,
    "charset"  => DATABASE_CHARSET,
    "collation" => DATABASE_COLLATION,
    "prefix"   => DATABASE_PREFIX
]);

$capsule->bootEloquent();