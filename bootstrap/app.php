<?php

define('BASE_PATH',     dirname(__DIR__));
define('APP_PATH',      BASE_PATH.'/app');
define('CONFIG_PATH',   BASE_PATH.'/config');
define('ROUTE_PATH',    BASE_PATH.'/route');
define('PUBLIC_PATH',   BASE_PATH.'/public');
define('STORAGE_PATH',  BASE_PATH.'/storage');

// whoops 错误提示
$whoops = new Whoops\Run;
$whoops->prependHandler(new Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Route 加载路由
use Eb\Core\Route;
require ROUTE_PATH.'/web.php';
Route::error(function() {
    throw new Exception("404 :: Not Found");
});
Route::dispatch();