<?php

Class AES_Profile extends AES_Basic {

    use openssl;

    public static function getSecretKey() {
        $token_aes_configs = Yaf_Registry::get('config')->profile->aes->toArray();
        return $token_aes_configs["secret_key"];
    }

    public static function getIv() {
        $token_aes_configs = Yaf_Registry::get('config')->profile->aes->toArray();
        return $token_aes_configs["iv"];
    }

}