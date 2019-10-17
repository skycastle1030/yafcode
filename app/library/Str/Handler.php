<?php
Class Str_Handler {

    function random_strings($length = 10, $type = null) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        switch ($type) {
            case "lowercase":
                $characters = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case "uppercase":
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case "letter":
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case "number":
                $characters = '0123456789';
                break;
            default:
                break;
        }

        $rand_strings = '';

        for ($i = 0; $i < $length; $i++) {
            $rand_strings .= $characters[rand(0, strlen($characters))];
        }

        return $rand_strings;
    }
}