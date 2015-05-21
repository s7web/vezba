<?php
require_once 'app/init.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);