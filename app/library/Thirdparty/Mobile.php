<?php

class Thirdparty_Mobile {


	public static function getCity($mobile) {
		$result = json_decode(file_get_contents('http://120.76.54.166:7802/find?phone='.$mobile), true);
        return $result['city'];
	}


}