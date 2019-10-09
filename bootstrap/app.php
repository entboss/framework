<?php

// whoops 错误提示
$whoops = new Whoops\Run();
$whoops->prependHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();

// 创建一个新的服务容器
$app = new Eb\Core\Application(
    dirname(__DIR__)
);

///////// A /////////////
// 连接数据库
$db_cfg = require $app['path.config'].'/database.php';
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

// View 加载视图
$app->instance('config', new Illuminate\Support\Fluent());
$app['config']['view.compiled'] = $app['path.storage'].'/view';
$app['config']['view.paths'] = [$app['path.view']];

// Route 加载路由
require $app['path.route'].'/web.php';
$request = Illuminate\Http\Request::createFromGlobals();
$response = $app['router']->dispatch($request);
$response->send();


///////// B /////////////
// // 向容器中注册基础绑定
// $app->singleton(
//     Eb\Contract\HttpKernel::class,
//     Eb\Core\HttpKernel::class
// );

// // 返回这个容器
// return $app;
