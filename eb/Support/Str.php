<?php
/**
 * Str
 *
 * @package    EntBoss
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 * @version    19.10.7
 *
 */

namespace Eb\Support;

class Str extends \Illuminate\Support\Str
{
    /**
     * 生成 uniqid Key.
     *
     * @return string the unique identifier
     */
    public static function get_uid()
    {
        return md5(uniqid(rand(), true));
    }
    
    /**
     * 在数字编号前面补0，默认6位数
     * 0 => 000000,1 => 000001,20 => 000020,432 => 000432.
     *
     * @param int $num
     * @param int $n
     *
     * @return string
     */
    public static function get_pad_id($num, $n = 6)
    {
        return str_pad((int) $num, $n, '0', STR_PAD_LEFT);
    }

    /**
     * 生成订单编码
     * O 20190101 - 010101 100.
     *
     * @param string $prefix
     *
     * @return string
     */
    public static function get_order_no($prefix = 'O')
    {
        $order_main = $prefix.date('Ymd-His').rand(100, 999);
    }

    /**
     * 为字符串添加前缀-一维数组.
     *
     * @param array  $data 数据源
     * @param string $file 导出文件名
     *
     * @return array
     */
    public static function set_str_prefix($data = [], $prefix = '')
    {
        if (empty($data) || empty($prefix)) {
            return $data;
        }
        $data = array_map(function ($v) use ($prefix) {
            return $prefix.$v;
        }, $data);

        return $data;
    }

}
