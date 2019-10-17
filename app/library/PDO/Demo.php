<?php
require_once "Medoo.php";
use Yaf\Registry;
class PDO_Demo extends Medoo
{

    private static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new PDO_Demo();
            $dbConfigs = self::$_instance->getDbConfigs();
            self::$_instance->initialize($dbConfigs);
        }
        return self::$_instance;
    }

    public static function newInstance()
    {
        self::$_instance = null;
        return self::getInstance();
    }

    protected function getDbConfigs()
    {
        $yafConfigs = Registry::get("config");
        $dbConfigs = $yafConfigs->mysql->laravel->local->toArray();
        $database = [
            'database_type' => 'mysql',
            'database_name' => $dbConfigs['database'],
            'server' => $dbConfigs['hostname'],
            'username' => $dbConfigs['username'],
            'password' => $dbConfigs['password'],
            'charset' => $dbConfigs['charset'],
            'port' => $dbConfigs['hostport'],
            'prefix' => $dbConfigs['prefix'],
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
            'command' => [
                'SET SQL_MODE=ANSI_QUOTES',
            ],
        ];
        if ($dbConfigs['socket'] != '') {
            $database['socket'] = $dbConfigs['socket'];
        }
        return $database;
    }
}
