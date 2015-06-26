<?php
/**
 * Custom error handler
 *
 * @param $errno
 * @param string $errstr
 * @param string $errfile
 * @param string $errline
 * @param \Monolog\Logger $logger
 *
 * @return void
 */
function errorHandler( $errno, $errstr, $errfile, $errline )
{

    $logger = new \Monolog\Logger('app_php_errors');
    $logger->pushHandler(new \Monolog\Handler\StreamHandler( SITE_PATH.'log/app_php_error.log', \Monolog\Logger::NOTICE ));
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
        case E_STRICT:
            $logger->addError( 'PHP Strict : '.$errstr.' in file: '.$errfile.' on line: '.$errline );
            break;

        case E_WARNING:
        case E_USER_WARNING:
            $logger->addWarning( 'PHP Warning : '.$errstr.' in file: '.$errfile.' on line: '.$errline );
            break;

        case E_ERROR:
        case E_USER_ERROR:
            $logger->addError( 'PHP Fatal : '.$errstr.' in file: '.$errfile.' on line: '.$errline );
            exit();

        default:
            $logger->addError( 'PHP Unknown : '.$errstr.' in file: '.$errfile.' on line: '.$errline );
            exit();
    }
}


function shutDownFunction() {
    $error = error_get_last();
    if ($error['type'] == 1) {
        $logger = new \Monolog\Logger('app_php_errors_fatal');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler( SITE_PATH.'log/app_php_error_fatal.log', \Monolog\Logger::NOTICE ));
        $logger->addError('Fatal error', $error);
    }
}
