<?php

function getPlatformNameByCode($code = "")
{
    $map = config("platform.map");
    if (array_key_exists($code, $map)) {
        return (string) $map[$code];
    }
    return "Unknown";
}

function getTS()
{
    // return strtotime('2019-10-03 0:10:5');
    return $_SERVER['REQUEST_TIME'];
}

/**
 * Function: 生成页面控件ID
 *
 * @return string
 */
function genControlID()
{
    return substr(md5($_SERVER['REQUEST_URI']), 8, 16);
}

/**
 * Function: 格式化时间戳
 *
 * @param int $timestamp
 * @param string $format datetime date  time 三种输出格式
 * @return string
 */
function formatTime($timestamp, $format = 'datetime')
{
    if (empty($timestamp)) {
        return '';
    }

    switch ($format) {
        case 'datetime':
            $result = date('Y-m-d H:i', $timestamp);
            break;
        case 'date':
            $result = date('Y-m-d', $timestamp);
            break;
        case 'time':
            $result = date('H:i', $timestamp);
            break;
        case 'full_datetime':
            $result = date('Y-m-d H:i:s', $timestamp);
            break;
        default:
            break;
    }
    return $result;
}

/**
 * Function: 生成静态文件版本号
 */
function genStaticVersion()
{
    return _DEBUG ? substr(md5(time()), 8, 16) : date('Ymd') . STATIC_VERSION;
}

function data_pack()
{
    $args = func_get_args();
    $args_counter = count($args[0]);

    $data = $args[1];

    $arr_pack = [];

    for ($i = 0; $i < ($args_counter); $i++) {
        $data_key = $args[0][$i];
        $arr_pack[$i] = $data[$data_key];
    }
    return $arr_pack;
}

function response(array $result)
{
    @header('Content-Type: application/json');
    exit(json_encode($result));
}

function success_response($msg = "OK")
{
    $result = [
        "success" => true,
        "message" => $msg,
    ];
    Api_LogModel::addResponse($result);
    @header('Content-Type: application/json');
    exit(json_encode($result));
}

function error_response($msg = "")
{
    $result = [
        "success" => false,
        "message" => $msg,
    ];
    Api_LogModel::addResponse($result);
    @header('Content-Type: application/json');
    exit(json_encode($result));
}

function validate_status(array $response)
{
    return $response['status'];
}

function time_ago(int $timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);

    if ($seconds <= 60) {
        return "Just Now";
    } else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    } else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hrs ago";
        }
    } else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    } else if ($weeks <= 4.3) //4.3 == 52/12
    {
        if ($weeks == 1) {
            return "a week ago";
        } else {
            return "$weeks weeks ago";
        }
    } else if ($months <= 12) {
        if ($months == 1) {
            return "a month ago";
        } else {
            return "$months months ago";
        }
    } else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

function config($file_alias = null)
{
    $file_fullname = CONFIG_PATH;
    $arr_file = explode(".", $file_alias);
    $file_exists = false;
    $arr_file_counter = count($arr_file);
    foreach ($arr_file as $key => $name) {
        if ($file_exists === true) {
            if ($key == $arr_file_counter) {
                break;
            } else {
                $arr_data = $arr_data[$name];
            }
        } else {
            $file_fullname .= $name;
            $file_real_fullname = $file_fullname . ".php";
            if (file_exists($file_real_fullname)) {
                $arr_data = include $file_real_fullname;
                $file_exists = true;
            }
        }
    }
    return $arr_data;
}

function birthyear2age($birthyear)
{
    if(empty($birthyear)){
        return '-';
    }
    return date("Y") - intval($birthyear);
}

function calculate_percent(int $divisor = 0, int $dividend = 0)
{
    return round((((float) ($divisor / $dividend)) * 100), 2) . '%';
}

//for view
function JSLoader($files)
{
    $arrFiles = explode("|", $files);
    foreach ($arrFiles as $file) {
        echo '<script src="' . JS_PATH . $file . '"></script>';
    }
}

function CSSLoader($files)
{
    $arrFiles = explode("|", $files);
    foreach ($arrFiles as $file) {
        echo '<link href="' . CSS_PATH . $file . '" rel="stylesheet">';
    }
}

function TMPLoader($file)
{
    include COMMON_PATH . $file;
}

//二维数组部分字段拼凑成数组
function TDAMerge2Arr($arrData, $arrFields)
{
    $arrData = (array) $arrData;
    $arrFields = (array) $arrFields;
    $arrMerged = [];
    foreach ($arrData as $arr) {
        foreach ($arr as $k => $v) {
            if (in_array($k, $arrFields)) {
                array_push($arrMerged, $v);
            }
        }
    }
    return $arrMerged;
}

function _mb_substr_replace($original, $replacement, $position, $length)
{
    $startString = mb_substr($original, 0, $position, "UTF-8");
    $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");
    $out = $startString . $replacement . $endString;
    return $out;
}

function phoneProtected($phone)
{
    return substr_replace($phone, '****', 3, 4);
    // return $phone;
}

function wechatProtected($wechat)
{
    return substr_replace($wechat, '****', 3, 4);
    // return $wechat;
}

function qqProtected($qq)
{
    return substr_replace($qq, '****', 3, 4);
    // return $qq;
}

function idCardProtected($idCard)
{
    if (preg_match("/^[A-Za-z0-9]+$/", $idCard)) {
        return substr_replace($idCard, '********', 7, 8);
    }
    return _mb_substr_replace($idCard, '********', 7, 8);
}

function phoneValidate($phone)
{
    if (preg_match("/(^1[3456789]{1}\d{9}$)|(^(5|6|8|9)\\d{7}$)/", $phone)) {
        return true;
    }
    return false;
}

function mysql_debugger()
{
    $pdo = PDO_CsmLocal::getInstance();
    $pdo_remote = PDO_CsmRemote::getInstance();
    // echo "<div class='mysql_debugger' style='display: none;'>";
    echo "<div class='mysql_debugger' style='display: normal;'>";
    foreach ($pdo->log() as $key => $query) {
        echo Debug_SqlFormatter::format($query);
    }
    foreach ($pdo_remote->log() as $key => $query) {
        echo Debug_SqlFormatter::format($query);
    }

    echo "</div>";
    echo "<script>
    layer.open({
      type: 1
      ,title: 'MySQL debugger'
      ,id: 'mysql_debugger'
      ,content: $('.mysql_debugger')
      ,shade: false
      ,offset: 'rb'
      ,maxmin: true
      ,resize: false
      ,scrollbar: true
    });
  </script>";
}

function getIpAddress()
{
    $direct_ip = '';
    // Gets the default ip sent by the user
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $direct_ip = $_SERVER['REMOTE_ADDR'];
    }
    // Gets the proxy ip sent by the user
    $proxy_ip = '';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $proxy_ip = $_SERVER['HTTP_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_VIA'])) {
        $proxy_ip = $_SERVER['HTTP_VIA'];
    } else if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
        $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
    } else if (!empty($_SERVER['HTTP_COMING_FROM'])) {
        $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
    }
    // Returns the true IP if it has been found, else FALSE
    if (empty($proxy_ip)) {
        // True IP without proxy
        return $direct_ip;
    } else {
        $is_ip = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxy_ip, $regs);
        if ($is_ip && (count($regs) > 0)) {
            // True IP behind a proxy
            return $regs[0];
        } else {
            // Can't define IP: there is a proxy but we don't have
            // information about the true IP
            return $direct_ip;
        }
    }
}

//statistic
function getCalledClass($deep = 2, $short = false)
{
    $called = debug_backtrace()[$deep];
    $class = $called['class'];
    unset($called);
    return $short ? str_replace('Model', '', explode('_', $class)[1]) : $class;
}

//statistic
function getCalledFunc($deep = 2)
{
    $called = debug_backtrace()[$deep];
    $func = $called['function'];
    unset($called);
    return $func;
}

//array to object
function arrayToObject(array $array)
{
    $object = new stdClass();
    if (count($array) > 0) {
        foreach ($array as $name => $value) {
            $name = strtolower(trim($name));
            if (!empty($name)) {
                if (is_array($value)) {
                    $object->$name = arrayToObject($value);
                } else {
                    $object->$name = $value;
                }
            }
        }
        return $object;
    } else {
        return $object;
    }
}

//mustache parse
function mustacheMatch(string $str)
{
    preg_match_all('/{{((?:[^}]|}[^}])+)}}/', $str, $_matches);
    return [
        'mustache' => $_matches[0],
        'value' => $_matches[1],
    ];
}

function dd()
{
    $arrs = func_get_args();
    foreach ($arrs as $arr) {
        echo "<hr /><pre>";
        print_r($arr);
    }
}

function ddq()
{
    $arrs = func_get_args();
    foreach ($arrs as $arr) {
        echo "<hr /><pre>";
        print_r($arr);
    }
    exit();
}

function emptyToString($param)
{
    if (empty($param)) {
        return '-';
    } else {
        return $param;
    }
}

function isTimestamp($timestamp)
{
    if (strtotime(date('d-m-Y H:i:s', $timestamp)) === (int) $timestamp) {
        return true;
    } else {
        return false;
    }
}

function alwasyTimestamp($str)
{
    if (isTimestamp($str)) {
        return $str;
    } else {
        return strtotime($str);
    }
}

function doDESEncrypt($data)
{
    $des = new DES_Basic();
    return $des->encrypt($data);
}

function timeAfterSeconds($seconds = 0, $time = null, $format = 'full_datetime')
{
    if ($time == null) {
        $time = date('Y-m-d H:i:s');
    }
    $timestamp = strtotime(sprintf("+%s seconds", intval($seconds)), strtotime($time));
    return formatTime($timestamp, $format);
}

function timeBeforeSeconds($seconds = 0, $time = null, $format = 'full_datetime')
{
    if ($time == null) {
        $time = date('Y-m-d H:i:s');
    }
    $timestamp = strtotime(sprintf("-%s seconds", intval($seconds)), strtotime($time));
    return formatTime($timestamp, $format);
}

function stringSplitToArrayByComma($str)
{
    $arr = explode(',', $str);
    return array_filter($arr);
}

function arrayMergeToStringByComma($arr)
{
    return implode(',', $arr);
}

//来电号码类型判断
function getIncomingCallType($incomingCall)
{
    $patternMobilePhone086 = "/^1[34578]{1}\d{9}$/";
    if (preg_match($patternMobilePhone086, $incomingCall)) {
        $type = 'mobile_phone';
    } else {
        $type = 'telephone';
    }
    return $type;
}

function birthyearToAge($birthyear)
{
    $birthyear = intval($birthyear);
    $age = date('Y') - $birthyear;
    $age = $age < 0 || $age > 100 ? 0 : $age;
    return $age;
}

//合同编号校验
function contractNoTest($contractNo)
{
    if (preg_match("/^[A-Z]{2}[1-9]{1}[1-9]{1}[0-9]{6}$/", $contractNo)) {
        return true;
    }
    return false;
};

function secondsConvertToHoursMinutes($init)
{
    $hours = floor($init / 3600);
    $minutes = floor(($init / 60) % 60);
    $seconds = $init % 60;
    $hours = strlen($hours) == 1 ? '0' . $hours : $hours;
    $minutes = strlen($minutes) == 1 ? '0' . $minutes : $minutes;
    $seconds = strlen($seconds) == 1 ? '0' . $seconds : $seconds;
    return sprintf("%s:%s:%s", $hours, $minutes, $seconds);
}

function idCardTest($id)
{
    $arrRe = [
        "(\d{17}(X))", //大陆二代身份证
        "(\d{18,18})", //大陆二代身份证
        "(\d{15,15})", //大陆一代身份证
        "([A-Z]?[A-Z]{1}\\d{6}\\([0-9A]{1}\\)|[1|5|7][0-9]{6}\([0-9Aa]\))", //香港身份证
        "([a-zA-Z][0-9]{9})", //澳门身份证
        "([A-Z]{1,2}\d{7})", //澳大利亚护照
        "([A-Z]{2}\d{6})", //比利时护照
        "([A-Z]{1}\d{7}[A-Z]{1})", //新加坡身份证
        "([A-Z]{2}\d{7})", //日本身份证
        "(\d{8,8})", //台胞证旧
        "(\d{9,9})", //美国护照
    ];
    $re = sprintf("/^(%s)$/", implode('|', $arrRe));
    if (!preg_match($re, $id)) {
        return false;
    }
    return true;
}

function generateCSMWikiAccessBuriedImageUrl()
{
    $yafConfigs = Yaf_Registry::get("config");
    $config = $yafConfigs->wiki->toArray();
    return sprintf("%s%s?token=%s", $config['basic_url'], $config['access_buried_url'], md5(date('Ymd') . $config['access_secret_key']));
}

/**
 * 导出excel(csv)
 * @data 导出数据
 * @headlist 第一行,列名
 * @fileName 输出Excel文件名
 */
function csvExport($data = [], $headlist = [], $fileName)
{

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
    header('Cache-Control: max-age=0');

    //打开PHP文件句柄,php://output 表示直接输出到浏览器
    $fp = fopen('php://output', 'a');

    //输出Excel列名信息
    foreach ($headlist as $key => $value) {
        //CSV的Excel支持GBK编码，一定要转换，否则乱码
        $headlist[$key] = iconv('utf-8', 'gbk', $value);
    }

    //将数据通过fputcsv写到文件句柄
    fputcsv($fp, $headlist);

    //计数器
    $num = 0;

    //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;

    //逐行取出数据，不浪费内存
    $count = count($data);
    for ($i = 0; $i < $count; $i++) {
        $num++;

        //刷新一下输出buffer，防止由于数据过多造成问题
        if ($limit == $num) {
            ob_flush();
            flush();
            $num = 0;
        }

        $row = $data[$i];
        foreach ($row as $key => $value) {
            $row[$key] = iconv('utf-8', 'gbk', $value);
        }

        fputcsv($fp, $row);
    }
    exit();
}

//将下划线命名转换为驼峰式命名
function convertUnderline2($str ,$ucfirst = true)
{
    $str = explode('_' , $str);
    foreach($str as $key=>$val)
        $str[$key] = ucfirst($val);
 
    if(!$ucfirst)
        $str[0] = strtolower($str[0]);
 
    return implode('' , $str);
}