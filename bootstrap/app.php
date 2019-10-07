<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH.'/app');
define('CONFIG_PATH', BASE_PATH.'/config');
define('ROUTE_PATH', BASE_PATH.'/route');
define('PUBLIC_PATH', BASE_PATH.'/public');
define('STORAGE_PATH', BASE_PATH.'/storage');

// whoops 错误提示
$whoops = new Whoops\Run();
$whoops->prependHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();

// 连接数据库
$db_cfg = require CONFIG_PATH.'/database.php';
if (isset($db_cfg['default']) && isset($db_cfg['connections'])) {
    $conn = $db_cfg['default'];
    if (isset($db_cfg['connections'][$conn]) && $db_cfg['connections'][$conn] != '') {
        $db_conn = $db_cfg['connections'][$conn];
        $db = new Illuminate\Database\Capsule\Manager();
        $db->addConnection($db_conn);
        $db->setAsGlobal();
        $db->bootEloquent();
    } else {
        throw new Exception('config/database.php error');
    }
}

// 创建并注册
$app = new Illuminate\Container\Container();
Illuminate\Container\Container::setInstance($app);
with(new Illuminate\Events\EventServiceProvider($app))->register();
with(new Illuminate\Routing\RoutingServiceProvider($app))->register();
with(new Illuminate\View\ViewServiceProvider($app))->register();
with(new Illuminate\Filesystem\FilesystemServiceProvider($app))->register();

// View 加载视图
$app->instance('config', new Illuminate\Support\Fluent());
$app['config']['view.compiled'] = STORAGE_PATH.'/view';
$app['config']['view.paths'] = [BASE_PATH.'/view'];

// Route 加载路由
require ROUTE_PATH.'/web.php';
$request = Illuminate\Http\Request::createFromGlobals();
$response = $app['router']->dispatch($request);
$response->send();
