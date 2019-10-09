<?php
/**
 * Arr
 *
 * @package    EntBoss
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 * @version    19.10.7
 *
 */

namespace Eb\Support;

class Arr extends \Illuminate\Support\Arr
{

    /**
     * 获取数组里面的id值，并以该值作为key生成新的数组.
     *
     * @param array 旧数组
     *
     * @return array 新数组
     */
    public static function get_id_array($arr)
    {
        $ret = [];
        if (is_array($arr) && count($arr)) {
            foreach ($arr as $item) {
                if (isset($item['id'])) {
                    $id = $item['id'];
                    unset($item['id']);
                    foreach ($item as &$repeat) {
                        if (is_array($repeat)) {
                            $repeat = get_id_array($repeat);
                        }
                    }
                    $ret[$id] = $item;
                }
            }
        }

        return $ret;
    }


    /**
     * 数组转化成对象
     *
     * @param array $array
     *
     * @return object
     */
    public static function array_to_object($array)
    {
        if ($array) {
            return (object) $array;
        } else {
            return $array;
        }
    }

    /**
     * 对象转化成数组.
     *
     * @param objcet $object
     *
     * @return array
     */
    public static function object_to_array($object)
    {
        $arr = [];
        $_arr = is_object($object) ? get_object_vars($object) : $object;
        if ($_arr) {
            foreach ($_arr as $key => $value) {
                $value = (is_array($value) || is_object($value)) ? object_to_array($value) : $value;
                $arr[preg_replace('/^.+\0/', '', $key)] = $value;
            }
        }

        return $arr;
    }

    /**
     * 对数组进行重新分组，如groupBy操作.
     *
     * @param array  $array
     * @param string $field
     *
     * @return array
     */
    public static function array_group_by($array, $field)
    {
        $arr = [];
        foreach ($array as $item) {
            $name = $item[$field];
            unset($item[$field]);
            $arr[$name]['title'] = $name;
            $arr[$name]['item'][] = $item;
        }

        return $arr;
    }

    
}
