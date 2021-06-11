<?php
/*
 * Modified: preppend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

$app = new \Phalcon\Config\Adapter\Yaml(BASE_PATH . '/app/config/app.yml');
$holiday = new \Phalcon\Config\Adapter\Yaml(BASE_PATH . '/app/config/holiday.yml');
$aws = new \Phalcon\Config\Adapter\Yaml(BASE_PATH . '/app/config/aws.yml');
$sns = new \Phalcon\Config\Adapter\Yaml(BASE_PATH . '/app/config/sns.yml');
$database = new \Phalcon\Config\Adapter\Yaml(BASE_PATH . '/app/config/database.yml');
$env = $_SERVER['ENV']; //nginx.conf で設定した環境変数を読み込む

return new \Phalcon\Config([
    'app' => $app->$env,
    'holiday' => $holiday->holiday,
    's3' => $aws->s3,
    'sns' => $sns,
    'database' => [
        'adapter' => 'Mysql',
        'host' => $database->$env['host'],
        'username' => $database->$env['username'],
        'password' => $database->$env['password'],
        'dbname' => $database->$env['dbname'],
        'charset' => 'utf8',
    ],
    'application' => [
        'appDir' => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir' => APP_PATH . '/models/',
        'migrationsDir' => APP_PATH . '/migrations/',
        'viewsDir' => APP_PATH . '/views/',
        'pluginsDir' => APP_PATH . '/plugins/',
        'libraryDir' => APP_PATH . '/library/',
        'cacheDir' => BASE_PATH . '/cache/',
        'baseUri' => '',
    ]
]);
