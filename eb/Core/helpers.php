<?php
/**
 * Helpers.
 *
 * @copyright  Copyright (c) 2019 EntBoss (http://www.entboss.com)
 * @license    http://www.entboss.com/license
 * @author     EntBoss Team
 *
 * @version    3.0
 */
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;
use Maatwebsite\Excel\Facades\Excel;

if (!function_exists('get_uid')) {
    /**
     * 生成 uniqid Key.
     *
     * @return string the unique identifier
     */
    function get_uid()
    {
        return md5(uniqid(rand(), true));
    }
}

if (!function_exists('get_pad_id')) {
    /**
     * 在数字编号前面补0，默认6位数
     * 0 => 000000,1 => 000001,20 => 000020,432 => 000432.
     *
     * @param int $num
     * @param int $n
     *
     * @return string
     */
    function get_pad_id($num, $n = 6)
    {
        return str_pad((int) $num, $n, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('get_order_no')) {
    /**
     * 生成订单编码
     * O 20190101 - 010101 100.
     *
     * @param string $prefix
     *
     * @return string
     */
    function get_order_no($prefix = 'O')
    {
        $order_main = $prefix.date('Ymd-His').rand(100, 999);
    }
}

if (!function_exists('get_id_array')) {
    /**
     * 获取数组里面的id值，并以该值作为key生成新的数组.
     *
     * @param array 旧数组
     *
     * @return array 新数组
     */
    function get_id_array($arr)
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
}

if (!function_exists('is_mobile')) {
    /**
     * Check if mobile.
     *
     * @return bool
     */
    function is_mobile()
    {
        $mobile = false;
        $url = \Request::url();
        $pos = stripos($url, 'm.');
        if ($pos !== false) {
            $mobile = true;
        } else {
            $agent = new Agent();
            $mobile = $agent->isMobile();
        }

        return $mobile;
    }
}

if (!function_exists('get_uri')) {
    /**
     * 获取URI地址
     *
     * @return string
     */
    function get_uri()
    {
        return trim(\Request::path(), '/');
    }
}

if (!function_exists('get_domain')) {
    /**
     * 获取域名.
     *
     * @return string
     */
    function get_domain($host = '')
    {
        if (\Request::input('user')) {
            $store_id = \Request::input('user');
            $stores = config('stores');
            if (isset($stores[$store_id]) && $stores[$store_id]) {
                return $stores[$store_id];
            }
        }
        if ($host == '') {
            $host = \Request::server('HTTP_HOST');
        }
        $main_domain = [
            'hexin',
            'hexincorp',
            'entboss',
            'hxcart',
            'affectcloud',
        ];
        $arr = explode('.', $host);
        switch (count($arr)) {
            case 1:
            case 2:
                $domain = $arr[0];
                break;
            case 3:
            case 4:
                if (in_array(strtolower($arr[1]), $main_domain)) {
                    $domain = $arr[0];
                } else {
                    $domain = $arr[1];
                }
                break;
            default:
                $domain = '';
                break;
        }
        if ($domain != '') {
            return strtolower($domain);
        }
    }
}

if (!function_exists('get_store_id')) {
    /**
     * 获取站点ID.
     *
     * @return int
     */
    function get_store_id($domain = '')
    {
        if (\Request::input('user')) {
            return \Request::input('user');
        }
        if ($domain == '') {
            $domain = get_domain();
        }
        $stores = config('stores');
        if ($stores && $domain) {
            foreach ($stores as $key => $value) {
                if ($value == $domain) {
                    return $key;
                }
            }
        }

        return 0;
    }
}

if (!function_exists('get_act')) {
    /**
     * 获取方法名Action.
     *
     * @return string
     */
    function get_act()
    {
        $route = \Route::currentRouteAction();
        list($controller, $action) = explode('@', $route);

        return $action;

        // $route = \Route::current()->getActionName();
        // list($controller, $action) = explode('@', $route);
        // return ['controller' => $controller, 'action' => $action];
    }
}

if (!function_exists('get_ctrl')) {
    /**
     * 获取控制器类名Controller.
     *
     * @return string
     */
    function get_ctrl()
    {
        $route = \Route::currentRouteAction();
        list($prefix, $controller) = explode('\\', $route);

        return $controller;
    }
}

if (!function_exists('get_ip')) {
    /**
     * 获取IP地址
     *
     * @return string
     */
    function get_ip($long = true, $ip_addr = 0)
    {
        if ($ip_addr != 0) {
            $ip = str_contains($ip_addr, '.') ? $ip_addr : long2ip($ip_addr);
        } else {
            $ip = \Request::ip();
        }
        if ($long) {
            return ip2long($ip);
        } else {
            return $ip;
        }
    }
}

if (!function_exists('array_to_object')) {
    /**
     * 数组转化成对象
     *
     * @param array $array
     *
     * @return object
     */
    function array_to_object($array)
    {
        if ($array) {
            return (object) $array;
        } else {
            return $array;
        }
    }
}

if (!function_exists('object_to_array')) {
    /**
     * 对象转化成数组.
     *
     * @param objcet $object
     *
     * @return array
     */
    function object_to_array($object)
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
}

if (!function_exists('array_group_by')) {
    /**
     * 对数组进行重新分组，如groupBy操作.
     *
     * @param array  $array
     * @param string $field
     *
     * @return array
     */
    function array_group_by($array, $field)
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

if (!function_exists('session_get_id')) {
    /**
     * 获取 Session ID.
     *
     * @return string
     */
    function session_get_id()
    {
        return \Session::getId();
    }
}

if (!function_exists('session_get')) {
    /**
     * 获取 Session 值
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    function session_get($key, $value = null)
    {
        $item = session($key);
        if ($item) {
            return $item;
        } elseif ($value != null) {
            return session_set($key, $value);
        } else {
            return '';
        }
    }
}

if (!function_exists('session_set')) {
    /**
     * 设置 Session 值
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    function session_set($key, $value)
    {
        session([$key => $value]);

        return $value;
    }
}

if (!function_exists('session_clear')) {
    /**
     * 清除 Session 值
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    function session_clear($key)
    {
        $item = session($key);
        \Session::forget($key);

        return $item;
    }
}

if (!function_exists('cache_get_key')) {
    /**
     * 获取 Cache Key.
     *
     * @param string $key
     *
     * @return string
     */
    function cache_get_key($key = '')
    {
        if ($key == '') {
            $key = get_uri();
        }
        if ($key == '/') {
            $key = 'index';
        }
        $cache_key = md5(strtolower(str_ireplace(
            ['http://',    'https://', '/',    '&',    '+',    '='],
            ['',           '',         '.',    '.',    '.',    '.'],
            get_store_id().'.'.get_domain().'.'.$key
        )));

        return $cache_key;
    }
}

if (!function_exists('cache_get')) {
    /**
     * 获取 Cache 值
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    function cache_get($key, $value = null)
    {
        $old_key = $key;
        $item = cache($key);
        if ($item) {
            return unserialize($item);
        } elseif ($value != null) {
            return cache_set($old_key, $value);
        } else {
            return '';
        }
    }
}

if (!function_exists('cache_set')) {
    /**
     * 设置 Cache 值
     *
     * @param string $key
     * @param string $value
     * @param int    $minutes
     *
     * @return string
     */
    function cache_set($key, $value, $minutes = 60)
    {
        if (config('api.cache_time')) {
            $minutes = config('api.cache_time');
        }
        $time = Carbon::now()->addMinutes($minutes);
        if ($value) {
            cache([$key => serialize($value)], $time);
        }

        return $value;
    }
}

if (!function_exists('cache_clear')) {
    /**
     * 清除 Cache 值
     *
     * @param string $key
     *
     * @return string
     */
    function cache_clear($key)
    {
        $ret = [$key => unserialize(cache($key))];
        \Cache::forget($key);

        return $ret;
    }
}

if (!function_exists('parse_xml')) {
    /**
     * 用 simplexml_load_string 函数初步解析 XML，返回值为对象，再通过 normalizer_xml 函数将对象转成数组.
     *
     * @param $xml
     *
     * @return array|null
     */
    function parse_xml($xml)
    {
        return normalizer_xml(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOBLANKS));
    }
}

if (!function_exists('normalizer_xml')) {
    /**
     * 将 XML 解析之后的对象转成数组.
     *
     * @param $object
     *
     * @return array|null
     */
    function normalizer_xml($object)
    {
        $result = null;
        if (is_object($object)) {
            $object = (array) $object;
        }
        if (is_array($object)) {
            foreach ($object as $key => $value) {
                $res = normalizer_xml($value);
                if (('@attributes' === $key) && ($key)) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $object;
        }

        return $result;
    }
}

if (!function_exists('str_to_url')) {
    /**
     * 字符串转成 url 地址
     *
     * @param string $str
     *
     * @return string url
     */
    function str_to_url($str)
    {
        $codes = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ];

        foreach ($codes as $key => $value) {
            $str = preg_replace("/($value)/i", $key, $str);
        }

        return strtolower(preg_replace(
            ['/[^a-zA-Z0-9\s-]/', '/[\s-]+|[-\s]+|[--]+/', '/^[-\s_]|[-_\s]$/'],
            ['', '-', ''],
            strtolower($str)));
    }
}

if (!function_exists('filter_name')) {
    /**
     * 清除名称的特殊字符.
     *
     * @param string $name
     *
     * @return string
     */
    function filter_name($name)
    {
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        if (preg_match($regex, $name) or str_contains($name, 'base64')) {
            return '';
        }

        return $name;
    }
}
if (!function_exists('para')) {
    /**
     * 获取参数的值
     *
     * @param array  $data
     * @param string $key
     *
     * @return array
     */
    function para($data, $key)
    {
        return (isset($data[$key]) ? $data[$key] : \Request::input($key)) ?? '';
    }
}

if (!function_exists('parse_txt_tag')) {
    /**
     * 获取上传TXT中的标签.
     *
     * @param string $line
     * @param string $tag_name
     *
     * @return string
     */
    function parse_txt_tag($line, $tag_name)
    {
        $prefix = '[tag:';
        if (stripos($line, $prefix) === false) {
            $prefix = '[标签:';
        }
        $tag = $prefix.$tag_name.']';
        $str = '';
        if ($tag_name != '' && stripos($line, $tag) !== false) {
            $start = stripos($line, $tag) + strlen($tag);
            $end = stripos($line, $prefix, $start);
            $length = $end - $start;
            $str = trim(substr($line, $start, $length));
            if ($tag_name == 'short_description' || $tag_name == 'products_description') {
                $str = htmlspecialchars($str);
            } else {
                if ($tag_name == 'products_price' || $tag_name == 'specials_price') {
                    $str = str_ireplace('$', '', $str);
                    $str = str_ireplace(',', '.', $str);
                }
            }
        }

        return $str;
    }
}
if (!function_exists('get_json_data')) {
    /**
     * 获取URL的数据.
     *
     * @param string $url
     * @param array  $param
     * @param string $method
     *
     * @return string
     */
    function get_json_data($url, $param = [], $method = 'get')
    {
        $http = new \GuzzleHttp\Client();
        if ($method == 'get') {
            $token = '';
            if (isset($param['token'])) {
                $token = $param['token'];
            }
            if ($param) {
                $url .= '?'.http_build_query($param);
            }
            $response = $http->request('GET', $url, [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ],
            ]);
        } else {
            $response = $http->post($url, ['form_params' => $param]);
        }
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }
}

if (!function_exists('request_form')) {
    /**
     * 生成提交表单.
     *
     * @param string $action
     * @param string $hiddenField
     * @param string $target
     * @param string $formId
     * @param string $method
     *
     * @return string
     */
    function request_form($action, $hiddenField = null, $target = '_parent', $formId = 'myForm', $method = 'post')
    {
        if ($target == 'hidden') {
            $html = "<iframe name='myFrame' id='myFrame' frameborder='0' scrolling='no' style='display:none;'></iframe>";
            $target = 'myFrame';
        } elseif ($target == 'iframe') {
            $html = "<iframe name='myFrame' id='myFrame' frameborder='0' scrolling='auto' height='1050' width='950'></iframe>";
            $target = 'myFrame';
        } else {
            $html = '';
        }
        $html .= "<form action='$action' method='$method' id='$formId' name='$formId' target='$target'>";
        foreach ($hiddenField as $k => $v) {
            $html .= "<input type=hidden name='$k' id='$k' value='$v'>";
        }
        $html .= '</form>';
        $html .= "<script type='text/javascript'>window.status='$action';document.getElementById('$formId').submit();</script>";

        return $html;
    }
}

if (!function_exists('request_http')) {
    /**
     * 生成 HTTP 请求表单.
     *
     * @param string $ip             Target IP/Hostname
     * @param string $uri            Target URI
     * @param string $verb           HTTP Request Method (GET and POST supported)
     * @param string $port           Target TCP port
     * @param string $getdata        HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2')
     * @param string $postdata       HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2')
     * @param string $cookie         HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2')
     * @param string $custom_headers Custom HTTP headers ie. array('Referer: http://localhost/
     * @param string $timeout        Socket timeout in seconds
     * @param string $req_hdr        Include HTTP request headers
     * @param string $res_hdr        Include HTTP response headers
     *
     * @return string
     */
    function request_http($ip, $uri = '/', $verb = 'GET', $port = 80, $getdata = [], $postdata = [], $cookie = [], $custom_headers = [], $timeout = 1, $req_hdr = false, $res_hdr = false)
    {
        $ret = '';
        $verb = strtoupper($verb);
        $cookie_str = '';
        $getdata_str = count($getdata) ? '?' : '';
        $postdata_str = '';
        foreach ($getdata as $k => $v) {
            $getdata_str .= urlencode($k).'='.urlencode($v).'&';
        }
        foreach ($postdata as $k => $v) {
            $postdata_str .= urlencode($k).'='.urlencode($v).'&';
        }
        foreach ($cookie as $k => $v) {
            $cookie_str .= urlencode($k).'='.urlencode($v).'; ';
        }
        $crlf = "\r\n";
        $req = $verb.' '.$uri.$getdata_str.' HTTP/1.1'.$crlf;
        $req .= 'Host: '.$ip.$crlf;
        $req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12'.$crlf;
        $req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'.$crlf;
        $req .= 'Accept-Language: en-us,en;q=0.5'.$crlf;
        $req .= 'Accept-Encoding: deflate'.$crlf;
        $req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7'.$crlf;
        foreach ($custom_headers as $k => $v) {
            $req .= $k.': '.$v.$crlf;
        }
        if (!empty($cookie_str)) {
            $req .= 'Cookie: '.substr($cookie_str, 0, -2).$crlf;
        }
        if ($verb == 'POST' && !empty($postdata_str)) {
            $postdata_str = substr($postdata_str, 0, -1);
            $req .= 'Content-Type: application/x-www-form-urlencoded'.$crlf;
            $req .= 'Content-Length: '.strlen($postdata_str).$crlf.$crlf;
            $req .= $postdata_str;
        } else {
            $req .= $crlf;
        }
        if ($req_hdr) {
            $ret .= $req;
        }
        if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false) {
            return "Error $errno: $errstr\n";
        }
        stream_set_timeout($fp, 0, $timeout * 1000);
        fwrite($fp, $req);
        while (($line = fgets($fp)) != false) {
            $ret .= $line;
        }
        fclose($fp);
        if (!$res_hdr) {
            $ret = substr($ret, strpos($ret, "\r\n\r\n") + 4);
        }

        return $ret;
    }
}

if (!function_exists('get_remote_image')) {
    /**
     * 下载远程图片保存到本地.
     *
     * @param string $url      文件url
     * @param string $save_dir 保存文件目录
     * @param string $filename 保存文件名称
     * @param string $type     使用的下载方式
     *
     * @return array
     */
    function get_remote_image($url, $save_dir = '', $filename = '', $type = 0)
    {
        if (trim($url) == '') {
            return ['file_name' => '', 'save_path' => '', 'error' => 1];
        }
        if (trim($save_dir) == '') {
            $save_dir = './';
        }
        if (trim($filename) == '') {
            $ext = strtolower(strrchr($url, '.'));
            if ($ext != '.png' && $ext != '.jpg' && $ext != '.jpeg') {
                return ['file_name' => '', 'save_path' => '', 'error' => 3];
            }
            $filename = time().$ext;
        }
        if (0 !== strrpos($save_dir, '/')) {
            $save_dir .= '/';
        }
        //创建保存目录
        if (!file_exists($save_dir) && !mkdir($save_dir, 0755, true)) {
            return ['file_name' => '', 'save_path' => '', 'error' => 5];
        }
        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2 = @fopen($save_dir.$filename, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);

        return ['file_name' => $filename, 'save_path' => $save_dir.$filename, 'error' => 0];
    }
}

if (!function_exists('excel_read')) {
    /**
     * 导入Excel数据.
     *
     * @param string $file 文件路径
     *
     * @return array
     */
    function excel_read($file, $sheet = 'all')
    {
        $arr = new Collection();
        if (\File::exists($file)) {
            if ($sheet == 'all') {
                Excel::load($file, function ($reader) use (&$arr) {
                    $arr = $reader->all();
                });
            } else {
                Excel::selectSheetsByIndex($sheet)->load($file, function ($reader) use (&$arr) {
                    $arr = $reader->all();
                });
            }
        }

        return $arr;
    }
}

if (!function_exists('excel_write')) {
    /**
     * 导出Excel数据.
     *
     * @param array  $data 数据源
     * @param string $file 导出文件名
     *
     * @return array
     */
    function excel_write($cellData, $file)
    {
        Excel::create($file, function ($excel) use ($cellData) {
            $excel->sheet('Sheet1', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->store('xlsx');
    }
}

if (!function_exists('curl')) {
    /**
     * CURL请求
     *
     * @param $url 请求url地址
     * @param $method 请求方法 get post
     * @param null $post_data post数据数组
     * @param bool json 是否发送json数据 false:否 true:是
     * @param array      $headers 请求header信息
     * @param bool|false $debug   调试开启 默认false
     *
     * @return mixed
     */
    function curl($url, $method = 'GET', $post_data = null, $json = false, $headers = [], $debug = false)
    {
        $method = strtoupper($method); //转大写
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($post_data)) {
                    $tmpdatastr = is_array($post_data) ? http_build_query($post_data) : $post_data;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            case 'GET':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
                break;
        }
        $ssl = preg_match('/^https:\/\//i', $url) ? true : false;
        curl_setopt($ci, CURLOPT_URL, $url);
        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        if ($json) { //发送JSON数据
            curl_setopt($ci, CURLOPT_HEADER, 0);
            curl_setopt($ci, CURLOPT_HTTPHEADER,
                [
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length:'.strlen($post_data), ]
            );
        }
        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($post_data);
            echo "=====info===== \r\n";
            print_r($requestinfo);
            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);

        return $response;
        //return array($http_code, $response,$requestinfo);
    }
}

if (!function_exists('valid_date')) {
    /**
     * 检查指定字符串是否为日期格式 年-月-日.
     *
     * @param $date  日期字符串
     *
     * @return bool true 是日期格式     false 不是日期格式
     */
    function valid_date($date)
    {
        //匹配日期格式
        if (preg_match('/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/', $date, $parts)) {
            //检测是否为日期,checkdate为月日年
            if (checkdate($parts[2], $parts[3], $parts[1])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('set_str_prefix')) {
    /**
     * 为字符串添加前缀-一维数组.
     *
     * @param array  $data 数据源
     * @param string $file 导出文件名
     *
     * @return array
     */
    function set_str_prefix($data = [], $prefix = '')
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
