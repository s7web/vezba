#!/bin/bash
composer install
php app/console.php o:s:u -f
php app/console.php i:u
php app/console.php a

