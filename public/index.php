<?php

// 定义请求的开始时间
define('EB_START', microtime(true));

// composer 自动加载
require __DIR__.'/../vendor/autoload.php';

// 引入服务容器，实现服务容器的实例化，并注册核心类服务
require_once __DIR__.'/../bootstrap/app.php';
