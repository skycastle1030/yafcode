<?php

class Apis_Token {

    public static function generate(int $userid = 0) {
        $arr = [
            "userid" => $userid,
            "timestamp" => $_SERVER["REQUEST_TIME"],
            "salt" => Str_Handler::random_strings(16),
        ];

        return AES_Token::encryptArrayToJson($arr);
    }

    public static function timestampValidate(int $timestamp, bool $quit = false) {
        if (_DEBUG === true) {} else {
            if (abs($_SERVER["REQUEST_TIME"] - $timestamp) > APIS_TOKEN_EXPIRE) {
                return error_response(API_NO_EXPIRED_TOKEN, API_MSG_EXPIRED_TOKEN, [], $quit, StatusUnauthorized);
            }
        }
        return true;
    }

    public static function tokenExpireValidate(string $token = "") {
        $arr = AES_Token::decryptJsonToArray($token);
        $timestamp = $arr["timestamp"];
        if (abs($_SERVER["REQUEST_TIME"] - $timestamp) > APIS_TOKEN_EXPIRE) {
            return true;
        }
        return false;
    }

    public static function getUserid(string $token = "", bool $quit = false) {
        $arr = AES_Token::decryptJsonToArray($token);
        $timestamp = $arr["timestamp"];
        $userid = $arr["userid"];
        if (is_array($arr)) {
            if (is_numeric($timestamp) && is_numeric($userid) && self::timestampValidate($timestamp, $quit) === true) {
                return $userid;
            }
        }
        return error_response(API_NO_INVALID_TOKEN, API_MSG_INVALID_TOKEN, [], $quit, StatusOK);
    }
}
