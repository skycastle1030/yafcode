<?php
require_once "Elastic/Autoload.class.php";

use Elasticsearch\ClientBuilder;

/**
 * ES通用模型
 */
class Es_Common
{
    /**
     * 链接句柄
     * @param  array $config= 配置
     * @return object  $link
     */
    public static $link = null;

    /**
     * [初始化]
     * @return [type] [description]
     */
    public static function getInstance()
    {
        if (self::$link === null) {
            $config = Yaf_Registry::get("config")->elastic->toArray();
            $hosts = [
                [
                    'host' => !empty($config['host']) ? $config['host'] : '',
                    'port' => !empty($config['port']) ? $config['port'] : '',
                    'user' => !empty($config['user']) ? $config['user'] : '',
                    'pass' => !empty($config['pass']) ? $config['pass'] : '',
                ],
            ];
            Es_Common::$link = ClientBuilder::create()->setHosts($hosts)->build();
        }
        return self;
    }

    /**
     * 搜索数据处理
     * @param array $search
     */
    public static function _basicHandle($search)
    {
        if (is_array($search)) {
            $searchHandle = [];
            if (isset($search['members.stage_id']) && isset($search['stage_id'])) {
                if (strtolower($search['stage_id'][0]) == 'eq') {
                    unset($search['members.stage_id']);
                } else {
                    unset($search['stage_id']);
                }
            }
            foreach ($search as $key => &$value) {
                if (is_array($value)) {
                    foreach ($value as &$val) {
                        if (is_array($val)) {
                            foreach ($val as &$v) {
                                switch (strtolower($v)) {
                                    case 'egt':
                                        $v = 'gte';
                                        break;
                                    case 'elt':
                                        $v = 'lte';
                                        break;
                                    case 'lt':
                                        $v = 'lt';
                                        break;
                                    case 'gt':
                                        $v = 'gt';
                                        break;
                                    case 'eq':
                                        $v = 'eq';
                                        break;
                                    case 'like':
                                        $v = 'like';
                                        break;
                                    case 'in':
                                        $v = 'in';
                                        break;
                                }
                            }
                        } else {
                            switch (strtolower($val)) {
                                case 'egt':
                                    $val = 'gte';
                                    break;
                                case 'elt':
                                    $val = 'lte';
                                    break;
                                case 'lt':
                                    $val = 'lt';
                                    break;
                                case 'gt':
                                    $val = 'gt';
                                    break;
                                case 'eq':
                                    $val = 'eq';
                                    break;
                                case 'like':
                                    $val = 'like';
                                    break;
                                case 'in':
                                    $val = 'in';
                                    break;
                            }
                        }
                    }
                }
                $key = explode('.', $key);
                $key = array_pop($key);
                $searchHandle[$key] = $value;
            }
        }
        return $searchHandle;
    }

    /**
     * 基础语句转换
     * @param array $search
     */
    public static function _sqltoEsSql($search)
    {
        // 基础查询生成
        $query = [];
        foreach ($search as $key => $value) {
            if (is_array($value)) {
                if (count($value) == 2 && is_array($value[0]) && is_array($value[1]) && in_array($value[0][0], ['gte', 'lte', 'lt', 'gt']) && in_array($value[1][0], ['gte', 'lte', 'lt', 'gt'])) {
                    $query['constant_score']['filter']['bool']['must'][] = ['range' => [$key => [$value[0][0] => $value[0][1], $value[1][0] => $value[1][1]]]];
                } elseif ($value[0] == 'eq') {
                    $query['constant_score']['filter']['bool']['must'][] = ['term' => [$key => ['value' => $value[1]]]];
                } elseif (in_array($value[0], ['gte', 'lte', 'lt', 'gt'])) {
                    $query['constant_score']['filter']['bool']['must'][] = ['range' => [$key => [$value[0] => $value[1]]]];
                } elseif ($value[0] == 'like') {
                    $value[1] = trim($value[1], "%");
                    $query['constant_score']['filter']['bool']['must'][] = ["match_phrase" => [$key => $value[1]]];
                } elseif ($value[0] == 'in') {
                    if (!is_array($value[1])) {
                        $value[1] = explode(",", $value[1]);
                    }
                    $query['constant_score']['filter']['bool']['must'][] = ["terms" => [$key => $value[1]]];
                }
            } else {
                $query['constant_score']['filter']['bool']['must'][] = ['term' => [$key => ['value' => $value]]];
            }
        }
        return $query;
    }

    /**
     * 基础语句转换
     * @param array $search
     */
    public static function _handle($search)
    {
        $bascData = self::_basicHandle($search);
        $queryData = self::_sqltoEsSql($bascData);
        return $queryData;
    }

    /**
     * byte类型字段处理
     */
    public static function _byteHandle($value)
    {
        $value = is_numeric($value) && $value <= 127 && $value >= -128 ? intval($value) : 0;
        return $value;
    }

    /**
     * short类型字段处理
     */
    public static function _shortHandle($value)
    {
        $value = is_numeric($value) && $value <= 32767 && $value >= -32768 ? intval($value) : 0;
        return $value;
    }

    /**
     * integer类型字段处理
     */
    public static function _integerHandle($value)
    {
        $value = is_numeric($value) && $value <= 2147483647 && $value >= -2147483648 ? intval($value) : 0;
        return $value;
    }

    /**
     * long类型字段处理
     */
    public static function _longHandle($value)
    {
        $value = is_numeric($value) ? $value : 0;
        return $value;
    }

    /**
     * date类型字段处理
     */
    public static function _dateHandle($value)
    {
        $rule = '/^(\d{1,4}-\d{1,2}-\d{1,2})$/';
        $value = preg_match($rule, $value) ? $value : "1-1-1";
        return $value;
    }

    /**
     * 字段处理
     */
    public static function _fieldHandle($type, $value)
    {
        switch ($type) {
            case 'byte':
                $value = self::_byteHandle($value);
                break;
            case 'short':
                $value = self::_shortHandle($value);
                break;
            case 'integer':
                $value = self::_integerHandle($value);
                break;
            case 'long':
                $value = self::_longHandle($value);
                break;
            case 'date':
                $value = self::_dateHandle($value);
                break;
            default:
                $value = !empty($value) ? $value : "";
                break;
        }
        return $value;
    }
}
