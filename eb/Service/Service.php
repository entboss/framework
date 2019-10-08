<?php
/**
 * Service
 *
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 *
 * @version    19.10.8
 */

namespace Eb\Service;

class Service
{
    public $C;
    public $R;

    /*
     * 构造函数：获取配置文件，请求参数，缓存数据
     */
    public function __construct() {

    }

    /*
     * 获取Json数据，并缓存数据
     */
    public function result($data, $code = '200', $cache = false) {
        $arr = [
            'code'  => $code,
            'msg'   => config('api.code')[$code],
            'data'  => $data,
        ];
        if ($this->isCache() && $cache) {
            $key = cache_get_key(); // todo: 需要生成唯一key，当前错误
            cache_set($key, $arr);
        }

        return $arr;
    }

    /*
     * 获取配置文件
     */
    private function getConfig() {
        $cfg = [];
        $cfg_key = cache_get_key('apicfg');
        $cfg_cache = cache_get($cfg_key);
        if ($cfg_cache) {
            $cfg = $cfg_cache;
        } else {
            $cfg = config('api');
            cache_set($cfg_key, $cfg);
        }
        if (!isset($cfg['cache'])) {
            $cfg['cache'] = false;
        }
        if (!isset($cfg['store_id'])) {
            $cfg['store_id'] = get_store_id();
        }
        if (!isset($cfg['year'])) {
            $cfg['year'] = date('Y');
        }

        return array_to_object($cfg);
    }

    /*
     * 获取请求参数
     */
    private function getRequest() {
        $req = new \stdClass();
        $req->store_id = $this->C->store_id;
        $req->page = '1';
        $req->page_size = $this->C->page_size;
        $req->ctrl = get_ctrl();
        $req->act = get_act();
        $req->previous = url()->previous();
        $req->current = url()->current();
        $req->uri = get_uri();
        $req->ip = get_ip();
        $req->realip = get_ip(false);
        $p = \Request::input('p');
        if ($p > 0) {
            \Request::offsetSet('page', $p);
        }
        $limit = \Request::input('limit');
        if ($limit > 0) {
            \Request::offsetSet('page_size', $limit);
        }
        foreach (\Request::all() as $key => $value) {
            $req->$key = $value;
        }

        return $req;
    }

}
