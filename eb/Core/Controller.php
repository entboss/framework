<?php
/**
 * 控制器基类.
 *
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 *
 * @version    19.1
 */

namespace Eb\Core;

class Controller
{
    public $C;  // 配置全局变量
    public $R;  // 请求全局变量
    public $T;  // 模板全局变量

    /*
     * 构造函数：获取配置文件，请求参数，缓存文件
     */
    public function __construct()
    {
    }
}
