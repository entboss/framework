<?php
/**
 * 数据层基类
 *
 * @package    EntBoss
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 * @version    3.1
 *
 */

namespace Eb\Core;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    //不自动更新时间
    public $timestamps = false;
    //自定义数据表的主键
    //public $primaryKey = 'id';
    //主键不自动增加
    //public $incrementing = false;
    //字段黑名单
    protected $guarded = [];
    //字段列表
    protected static $fields = ['*'];
    //是否多站点
    protected static $mulit = true;

    /** 
     * 根据主键id获取数据对象 
     * 
     * @access public 
     * @param mixed $id 主键id
     * @return object 数据对象
     */
    public static function getItemById($id, $withs = []){
        return self::getItemByField('id', $id, $withs);
    }

    /**
     * 根据主键id获取数据对象
     *
     * @access public
     * @param mixed $id 主键id
     * @param array $fields 查询的字段
     * @return object 数据对象
     */
    public static function getItemByIdField($id, $fields = []){
        empty($fields) && $fields = self::$fields;
        $wheres = [
            ['id', '=', $id]
        ];
        return self::getItemByWhereBase($fields, $wheres);
    }

    /** 
     * 根据字段获取数据对象 
     * 
     * @access public 
     * @param mixed $field 字段
     * @param mixed $value 值
     * @return object 数据对象
     */
    public static function getItemByField($field, $value, $withs = []){
        $fields = self::$fields;
        $wheres = [
            [$field, '=', $value],
        ];
        $sorts = [];
        $joins = [];
        return self::getItemByWhereBase($fields, $wheres, $sorts, $joins, $withs);
    }

    /**
     * 根据查询条件获取数据对象
     *
	 * @access protected
     * @param mixed $fields 字段列表
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @param mixed $joins leftJoin语句, $joins = [ ['product_desc', 'product_desc.product_id', '=', 'product.id'] ];
     * @param mixed $withs with 预加载, $withs = ['product_desc', 'parent'];
     * @return object 数据对象
     */
    protected static function getItemByWhereBase($fields = ['*'], $wheres = [], $sorts = [], $joins = [], $withs = []){
        $type = 'item';
        return self::getWhereBase($type, $fields, $wheres, $sorts, $joins, $withs);
    }

    /**
	 * 根据条件获取指定数量和排序的数据集合
	 * 
	 * @access public
     * @param integer $limit 指定数量
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @return collection 数据集合
     */
    public static function getList($limit = 0, $sorts = [], $withs = []){
        $fields = self::$fields;
        $wheres = [];
        $joins = [];
        return self::getListBase($fields, $limit, $wheres, $sorts, $joins, $withs);
    }

    /**
     * 根据条件获取列表
     *
     * @access protected
     * @param mixed $fields 字段列表
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @param mixed $joins leftJoin语句, $joins = [ ['product_desc', 'product_desc.product_id', '=', 'product.id'] ];
     * @param mixed $withs with 预加载, $withs = ['product_desc', 'parent'];
     * @return object 数据对象
     */
    public static function getAllListByWhereBase($fields = ['*'], $wheres = [], $sorts = [], $joins = [], $withs = []){
        return self::buildQuery($fields, $wheres, $sorts, $joins, $withs)->get();
    }

    /** 
     * 根据条件获取指定数量和排序的数据集合 
     * 
     * @access public 
     * @param mixed $field 字段
     * @param mixed $value 值
     * @param integer $limit 指定数量
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @return object 数据集合
     */
    public static function getListByField($field, $value, $limit = 0, $sorts = [], $withs = []){
        $fields = self::$fields;
        $wheres = [
            [$field, '=', $value],
        ];
        $joins = [];
        return self::getListBase($fields, $limit, $wheres, $sorts, $joins, $withs);
    }

    /**
	 * 根据条件获取指定数量和排序的数据集合
	 * 
	 * @access protected
     * @param mixed $fields 字段列表
     * @param integer $limit 指定数量
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];    
     * @param mixed $joins leftJoin语句, $joins = [ ['product_desc', 'product_desc.product_id', '=', 'product.id'] ];
     * @param mixed $withs with 预加载, $withs = ['product_desc', 'parent'];
     * @return collection 数据集合
     */
    protected static function getListBase($fields = ['*'], $limit = 0, $wheres = [], $sorts = [], $joins = [], $withs = []){
        $type = 'top';
        return self::getWhereBase($type, $fields, $wheres, $sorts, $joins, $withs, $limit);
    }  
    
    /**
	 * 根据条件获取指定分页和排序的数据集合
	 * 
	 * @access public
     * @param integer $page_size 分页数量
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @return collection 数据集合
     */
    public static function getListByPage($page_size = 0, $sorts = [], $withs = []){
        $fields = self::$fields;
        $wheres = [];
        $joins = [];
        return self::getListByPageBase($fields, $page_size, $wheres, $sorts, $joins, $withs);
    }
    
    /** 
     * 根据条件获取指定分页和排序的数据集合 
     * 
     * @access public 
     * @param mixed $field 字段
     * @param mixed $value 值
     * @param integer $page_size 分页数量
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @return object 数据集合
     */
    public static function getListByPageField($field, $value, $page_size = 0, $sorts = [], $withs = []){
        $fields = self::$fields;
        $wheres = [
            [$field, '=', $value],
        ];
        $joins = [];
        return self::getListByPageBase($fields, $page_size, $wheres, $sorts, $joins, $withs);
    }

    /**
	 * 根据条件获取指定分页和排序的数据集合
	 * 
	 * @access protected
     * @param mixed $fields 字段列表
     * @param integer $page_size 分页数量
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];     
     * @param mixed $joins leftJoin语句, $joins = [ ['product_desc', 'product_desc.product_id', '=', 'product.id'] ];
     * @param mixed $withs with 预加载, $withs = ['product_desc', 'parent'];
     * @return collection 数据集合
     */
    protected static function getListByPageBase($fields = ['*'], $page_size = 0, $wheres = [], $sorts = [], $joins = [], $withs = []){
        $type = 'pager';
        return self::getWhereBase($type, $fields, $wheres, $sorts, $joins, $withs, $page_size);
    }

    /**
     * 根据条件计算数量
     *
     * @access protected
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @return collection 数据集合
     */
    protected static function getCountBase($wheres = []){
        $query = self::select();
        $query = self::buildWhere($query, $wheres);
        return $query->count();
    }

    /**
     * 根据条件获取指定数量或分页的数据集合
     * 
     * @access private
     * @param mixed $type item/top/pager
     * @param mixed $fields 字段列表 ['product.id', 'product_desc.name']
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];    
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];     
     * @param mixed $joins leftJoin语句, $joins = [ ['product_desc', 'product_desc.product_id', '=', 'product.id'] ];
     * @param mixed $withs with 预加载, $withs = ['product_desc', 'parent'];
     * @param mixed $num 指定数量
     * @return collection 数据集合
    */
    private static function getWhereBase($type, $fields = ['*'], $wheres = [], $sorts = [], $joins = [], $withs = [], $num = 0){
        $list = self::buildQuery($fields, $wheres, $sorts, $joins, $withs);
        if($type == 'item'){
            $list = $list->first();
        }elseif($type == 'pager'){
            if($num > 0){
                $page_size = $num;
            } else {
                $page_size = Config('api.page_size');
            }
            $list = $list->paginate($page_size)->appends(\Request::all());
        }else{
            if($num > 0){
                $list = $list->limit($num);
            }
            $list = $list->get();
        }
        return $list;
    }

    /**
	 * 新增单条记录
	 *
	 * @access public
     * @param mixed $item 对象或数组
     * @param bool $retid 是否返回id
     * @return integer 是否成功：成功返回id或true, 失败返回false
     */
    public static function addItem($item, $retid = true){
        if(is_object($item)){
            $arr = object_to_array($item);
        }elseif(is_array($item)){
            $arr = $item;
        }else{
            return false;
        }        
        $arr = self::checkMulit($arr, self::$mulit);
		if($retid){
			return self::insertGetId($arr);
		}else{
			return self::insert($arr);
		}
    }

    /**
	 * 更新指定id的记录
	 *
	 * @access public
     * @param integer $id 指定id
     * @param mixed $item 对象或数组
     * @return bool 是否成功
     */
    public static function editItem($id, $item){
        $wheres = [
            ['id', '=', $id]
        ];
        return self::updateItem($wheres, $item);
    }

    /**
	 * 更新指定属性的记录
	 *
	 * @access public
     * @param array $wheres
     * @param mixed $item 对象或数组
     * @return bool 是否成功
     */
    public static function updateItem($wheres, $item){
        return self::editByWhereBase($wheres, $item);
    }

    /**
	 * 更新指定条件的所有记录
	 *
	 * @access protected
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @param mixed $item 对象或数组
     * @return bool 是否成功
     */
    protected static function editByWhereBase($wheres, $item){
        if(is_object($item)){
            $arr = object_to_array($item);
        }elseif(is_array($item)){
            $arr = $item;
        }else{
            return false;
        }
        if($wheres){
            $list = self::select();
            $list = self::buildWhere($list, $wheres);
            return $list->update($arr);
        }else{
            return false;
        }
    }

    /**
	 * 删除指定ids的记录
	 *
	 * @access public
     * @param mixed $ids id数组或字符串
     * @return bool 是否成功
     */
    public static function delByIds($ids){
        return self::destroy($ids);
    }

    /**
	 * 删除指定条件的所有记录
	 *
	 * @access protected
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @return bool 删除成功与否
     */
    protected static function delByWhereBase($wheres){
        if($wheres){
            $list = self::select();
            $list = self::buildWhere($list, $wheres);
            return $list->delete();
        }else{
            return false;
        }
    }

    /**
	 * 生成Query语句
	 *
	 * @access private
     * @param mixed $fields 字段列表 ['product.id', 'product_desc.name']
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * @param mixed $sorts orderBy语句, $sorts = [ ['id', 'desc'], ['name', 'asc'] ];
     * @param mixed $joins leftJoin语句, $joins = [ ['product_desc', 'product_desc.product_id', '=', 'product.id'] ];
     * @param mixed $withs with 预加载, $withs = ['product_desc', 'parent'];
     * @return object 查询对象
    */
    private static function buildQuery($fields = ['*'], $wheres = [], $sorts = [], $joins = [], $withs = []){
        $list = self::select($fields);
        //leftJoin 语句
        for($i = 0; $i < count($joins); $i ++){
            $list = $list->leftJoin($joins[$i][0], $joins[$i][1], $joins[$i][2], $joins[$i][3]);
        }
        //where 语句
        $list = self::buildWhere($list, $wheres);
        //orederBy 语句
        for($i = 0; $i < count($sorts); $i ++){
            $list = $list->orderBy($sorts[$i][0], $sorts[$i][1]);
        }
        //with 预加载
        if($withs){
            $list = $list->with($withs);
        }
        return $list;
    }

    /**
	 * 生成Where语句
	 *
	 * @access private
     * @param mixed $list 查询对象
     * @param mixed $wheres where语句, $wheres = [ ['name', '=', 'my name', 'or'] ];
     * ['function', $para = [], [['name', '=', 'my name', 'or'], ['name', '=', 'my name', 'or']], 'or']
     * @return object 查询对象
    */
    private static function buildWhere($list, $wheres = []){
        $wheres = self::checkMulitWhere($wheres, self::$mulit);
        for($i = 0; $i < count($wheres); $i ++){
            $field = $wheres[$i][0];
            $operate = $wheres[$i][1];
            $value = isset($wheres[$i][2]) ? $wheres[$i][2] : '';
            $or = false;
            if(isset($wheres[$i][3]) && $wheres[$i][3] != ''){
                $tmp = strtolower($wheres[$i][3]);
                if($tmp == 'or'){
                    $or = true;
                }
            }
            if($field != '' && $field == 'function'){
                //or,and
                //if(is_array($operate)){

                    //self::buildWhere();
                //}
            }
            if( $field != '' && $value !== ''){
                if($operate == ''){
                    if($or){
                        $list = $list->orWhere($field, $value);
                    }else{
                        $list = $list->where($field, $value);
                    }
                }else{
                    $operate = strtolower($operate);
                    if( $operate == 'like'){
                        if($or){
                            $list = $list->orWhere($field, 'LIKE', "%" . trim($value) . "%");
                        }else{
                            $list = $list->where($field, 'LIKE', "%" . trim($value) . "%");
                        }
                    }elseif($operate == 'in'){
                        if($or){
                            $list = $list->orWhereIn($field, $value);
                        }else{
                            $list = $list->whereIn($field, $value);
                        }
                    }elseif($operate == 'notin'){
                        if($or){
                            $list = $list->orWhereNotIn($field, $value);
                        }else{
                            $list = $list->whereNotIn($field, $value);
                        }
                    }elseif($operate == 'find'){
                        if($or){
                            $list = $list->orWhereRaw("FIND_IN_SET(?, $field)", [$value]);
                        }else{
                            $list = $list->whereRaw("FIND_IN_SET(?, $field)", [$value]);
                        }
                    }elseif($operate == 'raw'){
                        if($or){
                        	if($value == ''){
                        		$list = $list->orWhereRaw($field);
	                        }else{
	                        	$list = $list->orWhereRaw($field, $value);
	                        }
                        }else{
                        	if($value == ''){
                        		$list = $list->whereRaw($field);
                                //whereRaw('option_value_id = size_option_value_id')
	                        }else{
	                        	$list = $list->whereRaw($field, $value);
                                //whereRaw('vip_ID > ? and vip_fenshu >= ?',[2,300])
	                        }
                        }
                    }else{
                        if($or){
                            $list = $list->orWhere($field, $operate, $value);
                        }else{
                            $list = $list->where($field, $operate, $value);
                        }
                    }
                }
            }
        }
        return $list;
    }

    private static function checkMulit($arr, $mulit = false){
        if($mulit){
            $store_id = get_store_id();
            if(count($arr) == count($arr, COUNT_RECURSIVE)){ // 一维数组
                $arr['store_id'] = $store_id;
            }else{  // 多维数组
                foreach($arr as $line){
                    $line['store_id'] = $store_id;
                }
            }
        }       
        return $arr;
    }

    private static function checkMulitWhere($wheres, $mulit = false){
        if($mulit){
            $add_store_id = true;
            $store_id = get_store_id();
            foreach ($wheres as $key => $value) {
                if(isset($value[0]) && isset($value[2]) && $value[0] == 'store_id' && $value[2] == '0'){
                    $add_store_id = false;
                    unset($wheres[$key]);
                }
            }
            if($add_store_id){
                array_unshift($wheres, [(new static)->table . '.store_id', '=', $store_id]);
            }
        }
        return array_values($wheres);
    }
}
