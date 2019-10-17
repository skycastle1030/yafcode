<?php

class Apis_WeixinApi {

    private static $grantType = 'client_credential';
    private static $appId  = 'wx0d0f79bbe73433ce';
    private static $secret = 'a78759208c0607785a23ea731dacfdd4';

    public static function getAccessToken(){
        $param = [
            'grant_type' => self::$grantType,
            'appId'      => self::$appId,
            'secret'     => self::$secret,
        ];
        //$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type='.self::$grantType.'&appId='.self::$appId.'&secret='.self::$secret;
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $getTokenInfo = self::geturl($url,$param);
        //        access_token	string	获取到的凭证
        //expires_in	number	凭证有效时间，单位：秒。目前是7200秒之内的值。
        //errcode	number	错误码
        //errmsg	string	错误信息

        if(!$getTokenInfo || empty($getTokenInfo) || $getTokenInfo['errcode']!=0){
            return false;
        }

        return ['token'=>$getTokenInfo['access_token'],'expires'=>$getTokenInfo['expires_in']];



    }

    /*
     * access_token	string		是	接口调用凭证
        path	string		是	扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
        width	number	430	否	二维码的宽度，单位 px。最小 280px，最大 1280px
        auto_color	boolean	false	否	自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调
        line_color	Object	{"r":0,"g":0,"b":0}	否	auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
        is_hyaline	boolean	false	否	是否需要透明底色，为 true 时，生成透明底色的小程序码
     *
     * */
    public static function getQrCode($width='430',$userId){

        //$autoColor=false,$lineColor=false,$lshyaline=false
        $token = self::getAccessToken();

        if(!$token || empty($token) || $token['expires']<=0){
            return false;
        }
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$token['token'];
        //$url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.
       // $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='. $token['token'];
        //csm-czg.qinggan110.com/api/accessapi/register?parents_user_id=
        $param = [

            'width'       => $width,
            'scene'       => 'parents_user_id='.$userId,
//            'auto_color'  => $autoColor,
//            'line_color'  =>$lineColor,
//            'is_hyaline'  =>$lshyaline,
        ];
        $qrcodeInfo = self::posturl($url,json_encode($param));
        $qrcodeArr  = json_decode($qrcodeInfo);
        if($qrcodeArr && $qrcodeInfo['errcode']>0){
            return ['error_code'=>1006,'message'=>'获取微信二维码失败'];
        }
        $dir = dirname(dirname(dirname(dirname(__FILE__)))).'/photo/';
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);

        }
        $fileName = $userId.'_'.time().'.jpg';
        $file = $dir.$fileName;
        $ret  = file_put_contents($file, $qrcodeInfo, true);

        if(!$ret){
            return ['error_code'=>1007,'message'=>'生成图片失败'];
        }
        $qiniu = new Thirdparty_Qiniu();
        $qiniuKey = $qiniu->putFile($file, $fileName);
        unlink($file);
        return $qiniuKey;

    }

//    public function sendCurl($url,$method,$vars=array()){
//
//
//        if(empty($url) || empty($method) || empty($vars)){
//            return false;
//        }
//
//        $ch = curl_init();
//        if($method == 'post'){
//            $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_URL,$url);
//            if(is_array($vars) && !empty($vars)) {
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
//            }
//        } else if($method == 'get'){
//            $headerArray =array("Content-type:application/json;","Accept:application/json");
//            if(is_array($vars) && !empty($vars)) {
//                $query = http_build_query($vars);
//                curl_setopt($ch, CURLOPT_URL,$url.'?'.$query);//将数组转化为字符串参数
//            }else{
//                curl_setopt($ch, CURLOPT_URL,$url);//传递进来的url后可能有参数
//            }
//        }
//        $ssl = substr($url, 0, 8) == "https://" ? true : false;
//        if ($ssl) {
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        }
//        curl_setopt($url,CURLOPT_HTTPHEADER,$headerArray);
//        $output = curl_exec($ch);
//        //关闭URL请求
//        curl_close($ch);
//        return json_decode($output,true);
//
//    }

    public function geturl($url,$vars){
        $headerArray =array("Content-type:application/json;","Accept:application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if(is_array($vars) && !empty($vars)) {
            $query = http_build_query($vars);
            curl_setopt($ch, CURLOPT_URL,$url.'?'.$query);//将数组转化为字符串参数
        }else{
            curl_setopt($ch, CURLOPT_URL,$url);//传递进来的url后可能有参数
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ssl = substr($url, 0, 8) == "https://" ? true : false;
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($url,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output,true);
        return $output;
    }


    public function posturl($url,$jsonData){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return json_encode(['errcode'=>40001,'errmsg'=>'curl falied. Error Info: '.curl_error($curl)]);
            exit;
        }
        curl_close($curl);

//        var_dump($result);
        return $result;

//            $ch = curl_init();
//            $header = "Accept-Charset: utf-8";
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $tmpInfo = curl_exec($ch);
//            return json_decode($tmpInfo);
    }

    public static function phpPost($url, $postArr, $timeout=60) {
//        $postVars = '';
//        foreach($postArr as $key=>&$val){
//            $postVars .= "&$key=$val";
//        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
       // curl_setopt($ch, CURLOPT_TIMEOUT,    $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postArr));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $ssl = substr($url, 0, 8) == "https://" ? true : false;
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);exit;

        return json_decode($result,true);
    }

}