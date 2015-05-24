## Easy Framework by s7design Developer reference

This framework is based on MVC concept, and it has PSR-0 structure for Controllers, Models and views. Also this project
using [Doctrine2](http://www.doctrine-project.org/) as ORM ( Object Relational Mapping ). Template engine inside this 
framework is [Twig](http://twig.sensiolabs.org/).

### Where to start?
To get your app ready you need to set up database connection and other system wide variables inside file
app/config/site_conf.php
```php
<?php

//site root path
define( 'SITE_PATH', __DIR__.'/../../' );
//application path
define( 'APP_PATH', __DIR__.'/../' );
//configuration path
define( 'CONFIG_PATH', __DIR__ );
//site name
define( 'SITE_NAME', 'Development Test' ); //Change site name if you need to, this is used to show site name in <title> tag
//define site url
define( 'SITE_URL', 'http://development.two/public' ); //change site URL

//default controller
define( 'DEFAULT_CTRL', 'home' ); //Default controller which will be loaded if user hit root
//default method
define( 'DEFAULT_METHOD', 'index' ); //Default method which will be loaded if user hit root

//debug mode
define( 'DEBUG_MODE', true );

//default lang
define( 'DEFAULT_LANG', 'en' );
//secure key for encrypt
define( 'SECURE_KEY', '2z7e7we2e77mjfkiqowc=+' );
//default encryption
define( 'DEFAULT_ENCRYPTION', 'blowfish' );

/*********************************************
 * *************DATABASE CONFIG***************
 *********************************************/
define( 'DATABASE_DRIVER', 'pdo_mysql' );
define( 'DATABASE_HOST', '127.0.0.1' );
define( 'DATABASE_USERNAME', 'root' );
define( 'DATABASE_PASSWORD', '' );
define( 'DATABASE_NAME', 'test' );
define( 'DATABASE_CHARSET', 'utf8' );
define( 'DATABASE_COLLATION', 'utf8_unicode_ci' );
define( 'DATABASE_PREFIX', '' );
```



