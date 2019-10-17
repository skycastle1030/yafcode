<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5
 * Time: 10:23
 */
class ApiController extends Controller_Abstract{
    public function getApi($parameter, $option = "foo") {
        return $parameter;
    }
    protected function client_can_not_see() {
    }

}
$service = new Yar_Server(new Api());
$service->handle();