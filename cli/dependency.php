<?php

$config = include_once(__DIR__.'/../config/config.php');

$injector = new Auryn\Injector;

/* eloquent init example
$databaseFactory = function() use ($config) {
    $capsule = new Capsule;
    $env = $config['environment'];
    $capsule->addConnection($config['db'][$env]);
    $capsule->bootEloquent();
    return $capsule->getConnection();
};
$injector->delegate('Illuminate\Database\Connection', $databaseFactory);
$injector->share('Illuminate\Database\Connection');
 * 
 */

return $injector;
