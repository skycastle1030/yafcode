<?php

class Statistic_Logger
{
    protected static $excuteTimestampStart = 0;
    protected static $excuteTimestampEnd = 0;

    public static function excuteTimestampStartSet()
    {
        self::$excuteTimestampStart = microtime(true);
    }

    public static function excuteTimestampEndSet()
    {
        self::$excuteTimestampEnd = microtime(true);
    }

    public static function getExcuteSpentMicrotime()
    {
        return self::$excuteTimestampEnd - self::$excuteTimestampStart;
    }

    /**
     * [log description]
     * @param  [bool] $operationResult [操作结果]
     * @param  string $primaryField    [标识主键]
     * @param  [string|array] $primary_id    [影响的相关记录/一般是主键或主键合集]
     * @return [int]                  [mysql inserted id]
     */
    protected static function logging(bool $operationResult, string $primaryField, $primaryId = 'undefined', $result = 'undefined')
    {
        Statistic_Logger::excuteTimestampEndSet();
        preg_match('/^\/([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)\/?([a-zA-Z0-9_]+)?/', $_SERVER["REQUEST_URI"], $matched);
        $matchedCounter = count($matched);
        if ($matchedCounter == 3) {
            $module = "index";
            $controller = $matched[1];
            $action = $matched[2];
        } elseif ($matchedCounter == 4) {
            $module = $matched[1];
            $controller = $matched[2];
            $action = $matched[3];
        } else {
            return;
        }
        $_primaryId = !is_array($primaryId) ? $primaryId : json_encode($primaryId);
        $_result = !is_array($result) ? $result : json_encode($result);
        $values = [
            "module" => $module,
            "controller" => $controller,
            "action" => $action,
            "primary_field" => $primaryField,
            "primary_id" => $_primaryId,
            "operation_result" => (int) $operationResult,
            "result" => $_result,
            'excute_spent' => Statistic_Logger::getExcuteSpentMicrotime(),
            "created_userid" => BasicModel::getLoggedInUserid(),
            "created_time" => getTS(),
        ];
        $pdo = PDO_CsmLocal::getInstance();
        $pdo->insert(LOCAL_PREFIX . "admin_statistic_logs", $values);
        if (_DEBUG == true || 2 > 1) {
            if ($pdo->errorCounter() > 0) {
                return error_response("写入行为日志时发生错误:log");
            }
        }
        return $pdo->id();
    }

    /**
     * [log 普通行为日志记录]
     * @param  string $primaryField [description]
     * @param  [type] $primaryId [description]
     * @param  [type] $result       [description]
     * @return [type]               [description]
     */
    public function log(string $primaryField, $primaryId, $result = [])
    {
        return self::logging(true, $primaryField, $primaryId, $result);
    }

    /**
     * [error 行为错误日志记录]
     * @param  string $primaryField [description]
     * @param  [type] $primaryId [description]
     * @param  string $message      [description]
     * @param  string $result       [description]
     * @return [type]               [description]
     */
    public function error(string $primaryField, $primaryId, $message = 'unknown')
    {
        $pdo = PDO_CsmLocal::getInstance();
        $insertedId = self::logging(false, $primaryField, $primaryId);
        //插入错误日志
        if (is_array($message)) {
            $message = json_encode($message);
        }
        $pdo->insert(LOCAL_PREFIX . "admin_statistic_error_logs", [
            "lid" => $insertedId,
            "message" => $message,
        ]);
        if (_DEBUG == true || 2 > 1) {
            if ($pdo->errorCounter() > 0) {
                return error_response("写入行为错误日志时发生错误:error");
            }
        }
    }
}
