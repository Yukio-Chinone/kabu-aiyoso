<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Crypt;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Session
     */
    $di->setShared('session', function () {
        $session = new Session();
        $session->start();
        return $session;
    });

    $di->set("crypt", function () {
        $crypt = new Crypt();
        $crypt->setKey('67UJGP,m#U9&'); // 任意の暗号化キーをセット
        return $crypt;
    });

    /**
     * Read services
     */
    include APP_PATH . "/config/services.php";

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
