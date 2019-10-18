<?php

class Yaf_server {
    public function some_method($parameter, $options = "foo") {
        $info = "welcome, {$parameter}, ".$_SERVER['REMOTE_ADDR'];
        return json_encode(array('res' => $info));
    }

    public function demo() {
        sleep(1);
        return "123";
    }
}