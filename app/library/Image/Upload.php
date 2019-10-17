<?php

class Image_Upload
{

    public static function generateUploadFilename(string $folder)
    {
        $staticDir = UPLOAD_PATH . $folder . DS . date("Y") . DS . date("m") . DS . date("d") . DS;
        $saveDir = $_SERVER["DOCUMENT_ROOT"] . $staticDir;
        $staticFileName = $staticDir . Str_Handler::random_strings(12) . ".jpg";
        $saveFileName = $_SERVER["DOCUMENT_ROOT"] . $staticFileName;
        if (!file_exists($saveDir)) {
            mkdir($saveDir, 0700, true);
        }
        return ["staticFileName" => $staticFileName, "saveFileName" => $saveFileName];
    }

    public static function upload($folder = "default")
    {
        $fileName = self::generateUploadFilename($folder);
        $input = file_get_contents('php://input');
        if ($input == '') {
            foreach ($_FILES as $file) {
                $input = $file['tmp_name'];
            }
            if (move_uploaded_file($input, $fileName["saveFileName"])) {
                return success_response(["img" => $fileName["staticFileName"]]);
            }
        } else {
            if (file_put_contents($fileName["saveFileName"], $input)) {
                return success_response(["img" => $fileName["staticFileName"]]);
            }
        }
        return error_response('上传失败');
    }
}
