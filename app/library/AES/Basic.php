<?php

trait openssl {

    public function encrypt(string $str) {

        $str = serialize($str);
        $data['iv'] = base64_encode(substr(self::getIv(), 0, 16));
        $data['value'] = openssl_encrypt($str, 'AES-256-CBC', self::getSecretKey(), 0, base64_decode($data['iv']));
        $str_encrypt = base64_encode(json_encode($data));
        return $str_encrypt;
    }

    public function decrypt(string $encrypt) {
        $encrypt = json_decode(base64_decode($encrypt), true);
        $iv = base64_decode($encrypt['iv']);
        $decrypt = openssl_decrypt($encrypt['value'], 'AES-256-CBC', self::getSecretKey(), 0, $iv);
        $decrypt = unserialize($decrypt);
        return $decrypt;
    }

    public function encryptString(string $str) {
        return self::encrypt($str);
    }

    public function encryptArrayToJson(array $arr) {
        $json = json_encode($arr);
        return self::encrypt($json);
    }

    public function decryptJsonToArray(string $encrypt) {
        $decrypt = self::decrypt($encrypt);
        $arr_decrypt = json_decode($decrypt, true);
        return $arr_decrypt;
    }

    public function decryptString(string $encrypt) {
        return self::decrypt($encrypt);
    }

}

Class AES_Basic {

}