<?php
const QINIU_ACCESSKEY = "sevjZHdCTKwjzoA32prSUq6fRMc38RNlB8MpLCJc";
const QINIU_CALLBACK_SECRET_KEY = "wQUscIAQTAdoL8ltiUYarUv7MqgXa_1ts6HNIoBQHnyV1kVe(NG62f8lcKryn9Ye3jOS5AXLVaJebJvnd";
class QiniuController extends CallbackController
{
    public function callbackAction()
    {
        $args = $this->Vals($this->getRequest(), ["key", "hash", "env", "secret_key"]);
        //var_dump($args);exit;
        if ($args["secret_key"] != QINIU_CALLBACK_SECRET_KEY || !stristr($_SERVER["HTTP_AUTHORIZATION"], QINIU_ACCESSKEY)) {
            return response(["error" => "Illegal Token"]);
        }
        $qiniu = new Thirdparty_Qiniu();
        $resp = [
            'success' => true,
            'name' => $args['key'],
            'file' => $qiniu->getAccessUrl($args['key'], 'imageView2/1/w/100/h/100/q/75%7Cimageslim'),
            'attachment' => $qiniu->getAccessUrl($args['key'], ''),
        ];
        return response($resp);
    }
}
