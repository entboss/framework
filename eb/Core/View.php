<?php
/**
 * 接口逻辑基类
 *
 * @package    EntBoss
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 * @version    3.1
 *
 */

namespace Eb\Core;

use Illuminate\Routing\Controller;

class View
{
    public $C;
    public $R;

    /*
     * 构造函数：获取配置文件，请求参数，缓存数据
     */
    public function __construct() {        
        $this->C = $this->getConfig();
        $this->R = $this->getRequest();
        //$this->cacheResult();
    }

    /*
     * 获取Json数据，并缓存数据
     */
    public function result($data, $code = '200', $cache = false){
        $arr = [
            'code'  => $code,
            'msg'   => config('api.code')[$code],
            'data'  => $data
        ];
        if($this->isCache() && $cache){
            $key = cache_get_key(); // todo: 需要生成唯一key，当前错误
            cache_set($key, $arr);
        }
        return $arr;
    }

    /**
     * 返回array错误格式
     *
     * @access protected
     * @return array
     */
    protected function set_err_msg($msg = ''){
        return [
            'errorCode' => 'fail',
            'errorMsg' => $msg
        ];
    }

    /**
     * 为image字段添加域名，并添加img_m缩略图字段
     * @param array $data
     * @return array
     */
    protected function setImageDomainAndAddImgm($data = []){
        if(empty($data) || empty($data['image'])){
            return $data;
        }
        $img_host = config('api.img_host');
        $image = ltrim($data['image'], '/');
        $data['image'] = $img_host . $image;
        $data['img_m'] = $img_host . 'thumb/150x195/' . $image;
        return $data;
    }

    protected function parseUrl($url, $name, $id, $dash = '', $postfix = ''){
        $ret_url = '#';
        if($url != ''){
            if(stripos($url, '/') === 0){
                $ret_url = $url;
            }else{
                $ret_url = '/' . $url;
            }
        }else{
            if($name != '' && $id != ''){
                $tmp_url = str_replace(
                    [' & ',  '&',   '%',    '/',  '\\',   "\r\n", "\r",   "\n",   ' ',  '--'],
                    ['-',    '-',   '-',    '-',  '-',    '-',    '-',    '-',    '-',   '-'],
                    $name);
                if($dash != ''){
                    $tmp_url .= $dash . $id;
                }else{
                    $tmp_url .= $id;
                }
                if($postfix != ''){
                    $tmp_url .= $postfix;
                }else{
                    $tmp_url .= '.html';
                }
                $ret_url = '/' . strtolower($tmp_url);
            }
        }
        return $ret_url;
    }

    protected function parseImage($item, $image, $postfix = ''){
        $image_ = $image;
        if('/' == substr($image,0, 1)){ //如果路径有加 / 则去除
            $image_ = substr($image, 1);
        }
        $item->img  = config('api.img_host') . $image_;
        $prefix     = config('api.img_host') . 'thumb/';
        $imgs       = config('api.image');
        foreach ($imgs as $key => $value) {
            if($postfix != ''){
                $item->{$key . $postfix} = $prefix . $value . $image;
            }else{
                $item->{$key} = $prefix . $value . $image;
            }
        }
        return $item;
    }

    /*
     * 获取配置文件
     */
    private function getConfig(){
        $cfg = [];
        $cfg_key = cache_get_key('apicfg');
        $cfg_cache = cache_get($cfg_key);
        if($cfg_cache){
            $cfg = $cfg_cache;
        }else{
            $cfg = config('api');
            cache_set($cfg_key, $cfg);
        }
        if(!isset($cfg['cache'])){
            $cfg['cache'] = false;
        }
        if(!isset($cfg['store_id'])){
            $cfg['store_id'] = get_store_id();
        }
        if(!isset($cfg['year'])){
            $cfg['year'] = date('Y');
        }
        return array_to_object($cfg);
    }

    /*
     * 获取请求参数
     */
    private function getRequest(){
        $req = new \stdClass();
        $req->store_id  = $this->C->store_id;
        $req->page      = '1';
        $req->page_size = $this->C->page_size;
        $req->ctrl      = get_ctrl();
        $req->act       = get_act();
        $req->previous  = url()->previous();
        $req->current   = url()->current();
        $req->uri       = get_uri();
        $req->ip        = get_ip();
        $req->realip    = get_ip(false);
        $p = \Request::input('p');
        if($p > 0){
            \Request::offsetSet('page', $p);
        }
        $limit = \Request::input('limit');
        if($limit > 0){
            \Request::offsetSet('page_size', $limit);
        }
        foreach(\Request::all() as $key => $value){
            $req->$key = $value;
        }
        return $req;
    }

    /*
     * 获取缓存数据
     */
    private function cacheResult(){        
        if($this->isCache()){            
            $key = cache_get_key(); // todo: 需要生成唯一key，当前错误
            $arr = cache_get($key);
            if($arr){
                print_r(\GuzzleHttp\json_encode($arr));
                exit;
            }
        }
    }

    /*
     * 是否启用缓存
     */
    private function isCache(){
        return config('api.cache');
    }
}
