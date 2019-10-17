<?php

class Statistic_Client {

    protected static $module = "";
    protected static $interface = "";
    protected static $timeMap = [];
    public static $arrCient = [];
    public static $args = [];
    protected static $reportAddress = [];
    protected static $bufferParams = [];
    protected static $userid;

    protected static $arrUAValueType = [
        "isMobileDevice" => "int",
        "isMobile" => "int",
        "isSpider" => "int",
        "isTablet" => "int",
        "isComputer" => "int",
        "major" => "int",
        "minor" => "int",
        "browser" => "string",
        "family" => "string",
        "version" => "string",
        "browserFull" => "string",
        "isUIWebview" => "int",
        "osMajor" => "int",
        "osMinor" => "int",
        "osBuild" => "int",
        "osPatch" => "int",
        "os" => "string",
        "osVersion" => "string",
        "osFull" => "string",
        "full" => "string",
        "uaOriginal" => "string",
    ];

    protected static function queryParams($arrCient) {
        $arrQueryParams = [
            "query_client" => $arrCient["query_client"],
            "user_agent" => $arrCient["user_agent"],
            "ip" => $arrCient["ip"],
            "referer" => $arrCient["referer"],
        ];
        return $arrQueryParams;
    }

    protected static function UAParser(string $userAgent = "") {
        $UAParser = new UA_Parser;
        $arrUserAgent = $UAParser->parse($userAgent);
        $newArrUserAgent = [];
        if (count($arrUserAgent) > 0) {
            foreach ($arrUserAgent as $field => $value) {
                $field = trim($field);
                $fieldRel = "UA" . $field;
                if ($value != "") {
                    switch (self::$arrUAValueType[$field]) {
                        case "int":
                            $newArrUserAgent[$fieldRel] = (int) $value;
                            break;
                        case "string":
                            $newArrUserAgent[$fieldRel] = trim((string) $value);
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $newArrUserAgent;
    }

    public static function tick(array $arrCient = [], array $apiArgs = [], array $reportAddress = []) {
        self::$module = getCalledClass(2, true);
        self::$interface = getCalledFunc();

        self::$arrCient = self::queryParams($arrCient);
        self::$reportAddress = $reportAddress;
        self::$userid = (int) $arrCient["userid"];
        self::$args = $apiArgs;

        if (count(self::$reportAddress) == 0) {
            $yafConfigs = Yaf_Registry::get("config");
            $statisticConfigs = $yafConfigs->statistic->udp_server->default->toArray();
            self::$reportAddress["host"] = $statisticConfigs["host"];
            self::$reportAddress["port"] = $statisticConfigs["port"];
        }
        unset($apiArgs["password"]);
        unset($apiArgs["retype_password"]);
        unset($apiArgs["new_password"]);
        unset($apiArgs["retype_new_password"]);
        $arrParams = [
            "APIModule" => self::$module,
            "APIInterface" => self::$interface,
            "APIQueryClient" => $arrCient["query_client"],
            "APIQueryIP" => $arrCient["ip"],
            "APIQueryReferer" => $arrCient["referer"],
            "APIArgs" => json_encode((array) $apiArgs),
            "APIUserid" => (int) self::$userid,
        ];
        self::$bufferParams = array_merge($arrParams, self::UAParser((string) $arrCient["user_agent"]));
        self::$timeMap[self::$module][self::$interface] = microtime(true);
        return self::$timeMap;
    }

    public static function report(int $code, string $msg) {
        $cost_time = (microtime(true) - self::$timeMap[self::$module][self::$interface]) * 1000;
        self::$bufferParams["APICostTime"] = $cost_time;
        self::$bufferParams["APICode"] = (int) $code;
        self::$bufferParams["APIMsg"] = (string) $msg;
        self::$bufferParams["APIQueryTimestamp"] = (int) $_SERVER["REQUEST_TIME"];
        self::$bufferParams["APIQueryDate"] = (int) date("Ymd");
        if (Cache_Archive::$cache === Cache_Archive::HAVE_CACHE) {
            self::$bufferParams['cache'] = 1;
        }
        $buffer = json_encode(self::$bufferParams);
        return self::sendData(self::$reportAddress, $buffer);
    }

    protected static function sendData(array $address, string $buffer) {
        $client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_SYNC);
        $client->connect($address["host"], $address["port"]);
        $client->send($buffer);
        // $recv = $client->recv();
        $client->close();
    }
}
