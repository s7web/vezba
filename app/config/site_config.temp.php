<?php
//site root path
define( 'SITE_PATH', __DIR__.'/../../' );
//application path
define( 'APP_PATH', __DIR__.'/../' );
//configuration path
define( 'CONFIG_PATH', __DIR__ );
//site name
define( 'SITE_NAME', 'Development Test' );
//define site url
define( 'SITE_URL', 'http://development.two/public' );

//default controller
define( 'DEFAULT_CTRL', 'home' );
//default method
define( 'DEFAULT_METHOD', 'index' );
//default redirect if user has not access
define('ACL_REDIRECT', 'login');
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
define( 'DATABASE_NAME', 'easy' );
define( 'DATABASE_PORT', 3306 );
define( 'DATABASE_CHARSET', 'utf8' );
define( 'DATABASE_COLLATION', 'utf8_unicode_ci' );
define( 'DATABASE_PREFIX', '' );

