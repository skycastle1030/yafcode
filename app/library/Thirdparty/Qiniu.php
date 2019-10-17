<?php

class Thirdparty_Qiniu
{

//    private $accessKey = "rEZ9sZtRLWKDQHmpj28UoiHCSM2r6Vq8Lw1oqlH8";
//    private $secretKey = "aZlFeKCJJIxifekSW6_HO-hR3Lwv6noOYqHFhTNr";
//    private $bucket = "wzlyprimeprivate";
    //private $bucket = "wzlymlts";

    private $accessKey = "sevjZHdCTKwjzoA32prSUq6fRMc38RNlB8MpLCJc";
    private $secretKey = "CpCzTqOPimtfNplgiiMKre5VGO0uKscFsJjbmuA0";
    private $bucket = "private-mlts";
    private $uploadUrl = "https://upload-z2.qiniup.com/";
    private $url;
    private $headers;
    private $body;
    private $method;
    private $expires = 36000;

    /**
     * 上传token
     * @return type
     */
    public function uploadToken($args = [])
    {
        $deadline = time() + $this->expires;
        $scope = $this->bucket;
        $params = [];
        $params['scope'] = $scope;
        $params['deadline'] = $deadline;
        $qiniuConfigs = Yaf_Registry::get("config")->third_party_app->qiniu;
        $params["callbackUrl"] = $qiniuConfigs->callback_url;
        $params["callbackBody"] = "secret_key=" . QINIU_CALLBACK_SECRET_KEY . "&key=$(key)&hash=$(etag)&env=" . $qiniuConfigs->env . "";
        $b = json_encode($params);
        $result['success']=1;
        $result['message']='数据获取成功';
        $result['token'] = $this->signWithData($b);
        $result['uploadUrl'] = $this->uploadUrl;
        return $result;
    }

    public function signWithData($data)
    {
        $data = $this->base64_urlSafeEncode($data);
        return $this->sign($data) . ':' . $data;
    }

    public function sign($data)
    {
        $hmac = hash_hmac('sha1', $data, $this->secretKey, true);
        return $this->accessKey . ':' . $this->base64_urlSafeEncode($hmac);
    }

    /**
     * 对提供的数据进行urlsafe的base64编码。
     *
     * @param string $data 待编码的数据，一般为字符串
     *
     * @return string 编码后的字符串
     * @link http://developer.qiniu.com/docs/v6/api/overview/appendix.html#urlsafe-base64
     */
    public function base64_urlSafeEncode($data)
    {
        $find = ['+', '/'];
        $replace = ['-', '_'];
        return str_replace($find, $replace, base64_encode($data));
    }

    /**
     * 上传文件到七牛
     *
     * @param $key        上传文件名
     * @param $filePath   上传文件的路径
     * @param $params     自定义变量，规格参考
     *                    http://developer.qiniu.com/docs/v6/api/overview/up/response/vars.html#xvar
     * @param $mime       上传数据的mimeType
     * @param $checkCrc   是否校验crc32
     *
     * @return array    包含已上传文件的信息，类似：
     *                                              [
     *                                                  "hash" => "<Hash string>",
     *                                                  "key" => "<Key string>"
     *                                              ]
     */
    public function putFile($filePath, $key = null, $params = null, $mime = 'application/octet-stream', $checkCrc = false)
    {
        if (!$this->checkTitle($filePath)) {
            //throw new \Exception("只能上传图片！", 1);
            return ['error_code'=>10001,'message'=>'只能上传图片'];
        }
        $uploadToken = $this->uploadToken();
        $Token = $uploadToken['token'];
        if (!file_exists($filePath)) {
            //throw new \Exception("上传失败", 1);
            return ['error_code'=>10002,'message'=>'上传失败'];
        }
        $file = fopen($filePath, 'rb');
        if ($file === false) {
           // throw new \Exception("上传失败", 1);
            return ['error_code'=>10003,'message'=>'上传失败'];
        }

        $stat = fstat($file);
        $size = $stat['size'];
        //4194304
        if ($size <= 20971520) {
            $data = fread($file, $size);
            fclose($file);
            if ($data === false) {
                //throw new \Exception("上传失败", 1);
                return ['error_code'=>10004,'message'=>'上传失败'];
            }
            $result = $this->put($Token, $key, $data, $params, $mime, $checkCrc);
            return ['error_code'=>0,'message'=>'上传成功','key'=>$result['name'],'image_url'=>$result['attachment']];
        } else {
            fclose($file);
            //throw new \Exception("文件太大", 1);
            return ['error_code'=>10005,'message'=>'文件太大'];
        }
    }

    public function checkTitle($filename) //判断文件类型

    {
        $file = fopen($filename, "rb");
        $bin = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
        $fileType = '';
        switch ($typeCode) {
            case 8075:
                $fileType = false;
                break;
            case 8297:
                $fileType = false;
                break;
            case 255216:
                $fileType = true;
                break;
            case 7173:
                $fileType = true;
                break;
            case 6677:
                $fileType = true;
                break;
            case 13780:
                $fileType = true;
                break;
            default:
                $fileType = false;
        }
        //Fix
        if ($strInfo['chars1'] == '-1' && $strInfo['chars2'] == '-40') {
            $fileType = true;
        }
        if ($strInfo['chars1'] == '-119' && $strInfo['chars2'] == '80') {
            $fileType = true;
        }
        return $fileType;
    }

    /**
     * 上传二进制流到七牛, 内部使用
     *
     * @param $upToken    上传凭证
     * @param $key        上传文件名
     * @param $data       上传二进制流
     * @param $params     自定义变量，规格参考
     *                    http://developer.qiniu.com/docs/v6/api/overview/up/response/vars.html#xvar
     * @param $mime       上传数据的mimeType
     * @param $checkCrc   是否校验crc32
     *
     * @return array    包含已上传文件的信息，类似：
     */
    public function put($upToken, $key, $data, $params, $mime, $checkCrc)
    {
        $fields = ['token' => $upToken];
        if ($key === null) {
            $fname = 'filename';
        } else {
            $fname = $key;
            $fields['key'] = $key;
        }
        $response = $this->multipartPost($this->uploadUrl, $fields, 'file', $fname, $data, $mime);
        return $response;
    }

    public function multipartPost($url, $fields, $name, $fileName, $fileBody, $mimeType = null, array $headers = [])
    {
        $data = [];
        $mimeBoundary = md5(microtime());
        foreach ($fields as $key => $val) {
            array_push($data, '--' . $mimeBoundary);
            array_push($data, "Content-Disposition: form-data; name=\"$key\"");
            array_push($data, '');
            array_push($data, $val);
        }
        array_push($data, '--' . $mimeBoundary);
        $mimeType = empty($mimeType) ? 'application/octet-stream' : $mimeType;
        $fileName = $this->escapeQuotes($fileName);
        array_push($data, "Content-Disposition: form-data; name=\"$name\"; filename=\"$fileName\"");
        array_push($data, "Content-Type: $mimeType");
        array_push($data, '');
        array_push($data, $fileBody);
        array_push($data, '--' . $mimeBoundary . '--');
        array_push($data, '');
        $body = implode("\r\n", $data);
        $contentType = 'multipart/form-data; boundary=' . $mimeBoundary;
        $headers['Content-Type'] = $contentType;
        $request = $this->Request('POST', $url, $headers, $body);
        return $this->sendRequest();
    }

    private function escapeQuotes($str)
    {
        $find = ["\\", "\""];
        $replace = ["\\\\", "\\\""];
        return str_replace($find, $replace, $str);
    }

    public function Request($method, $url, array $headers = [], $body = null)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function sendRequest()
    {
        $t1 = microtime(true);
        $ch = curl_init();
        $options = [
            CURLOPT_USERAGENT => $this->userAgent(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_URL => $this->url,
        ];
        // Handle open_basedir & safe mode
        if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
            $options[CURLOPT_FOLLOWLOCATION] = true;
        }
        if (!empty($this->headers)) {
            $headers = [];
            foreach ($this->headers as $key => $val) {
                array_push($headers, "$key: $val");
            }
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);

        if (!empty($this->body)) {
            $options[CURLOPT_POSTFIELDS] = $this->body;
        }
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $t2 = microtime(true);
        $duration = round($t2 - $t1, 3);
        $ret = curl_errno($ch);
        if ($ret !== 0) {
            curl_close($ch);
            throw new \Exception("上传失败", 1);
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = $this->parseHeaders(substr($result, 0, $header_size));
        $body = substr($result, $header_size);
        if ($code != 200) {
            // throw new \Exception("上传失败", 1);
            print_r($body);
            // return json_decode($body, false);
        } else {
            return json_decode($body, true);
        }
    }

    private function userAgent()
    {
        $sdkInfo = "7.1.3";
        $systemInfo = php_uname("s");
        $machineInfo = php_uname("m");
        $envInfo = "($systemInfo/$machineInfo)";
        $phpVer = phpversion();
        $ua = "$sdkInfo $envInfo PHP/$phpVer";
        return $ua;
    }

    private function parseHeaders($raw)
    {
        $headers = [];
        $headerLines = explode("\r\n", $raw);
        foreach ($headerLines as $line) {
            $headerLine = trim($line);
            $kv = explode(':', $headerLine);
            if (count($kv) > 1) {
                $headers[$kv[0]] = trim($kv[1]);
            }
        }
        return $headers;
    }

    public function getAccessUrl($fileKey, $params, $expired = 300)
    {
        $expired = intval($expired);
        $expired = $expired < 60 ? 60 : $expired;
        $e = getTS() + $expired;
        $domain = Yaf_Registry::get("config")->third_party_app->qiniu->access_domain;
        $_url = sprintf("%s/%s?e=%s&%s", $domain, $fileKey, $e, $params);
        return sprintf("%s&token=%s", $_url, $this->sign($_url));
    }
}
