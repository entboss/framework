<?php

namespace Eb\Core;

use Exception;
use Eb\Contract\Application;

class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Eb\Contract\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        define('BASE_PATH', dirname(__DIR__));
        define('APP_PATH', BASE_PATH.'/app');
        define('CONFIG_PATH', BASE_PATH.'/config');
        define('ROUTE_PATH', BASE_PATH.'/route');
        define('PUBLIC_PATH', BASE_PATH.'/public');
        define('STORAGE_PATH', BASE_PATH.'/storage');

        // whoops 错误提示
        $whoops = new \Whoops\Run();
        $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler());
        $whoops->register();

        mb_internal_encoding('UTF-8');
        date_default_timezone_set('UTC');

        // 连接数据库
        $db_cfg = require CONFIG_PATH.'/database.php';
        if (isset($db_cfg['default']) && isset($db_cfg['connections'])) {
            $conn = $db_cfg['default'];
            if (isset($db_cfg['connections'][$conn]) && $db_cfg['connections'][$conn] != '') {
                $db_conn = $db_cfg['connections'][$conn];
                $db = new \Illuminate\Database\Capsule\Manager();
                $db->addConnection($db_conn);
                $db->setAsGlobal();
                $db->bootEloquent();
            } else {
                throw new Exception('config/database.php error');
            }
        }
    }

}
