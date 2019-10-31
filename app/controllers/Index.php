<?php 

use Yaf\Dispatcher;
use Yaf\Controller_Abstract;
use Yaf\Application;
use Yaf\Registry;

class IndexController extends Controller_Abstract
{
	public function indexAction()
	{

        $arrConfig  = Application::app()->getConfig();
        $yafConfigs = Registry::get("config");

//        $pdo = PDO_Demo::getInstance();
//        $ruleList = $pdo->insert('roles',['name'=>'rules','remark'=>'jaskdjfdfdfdsdfs','order'=>9,'status'=>3]);
  //      var_dump($ruleList);exit;


//           echo '1%3='.(1%3).'<br>';
//           echo '2%3='.(2%3).'<br>';
//           echo '3%3='.(3%3).'<br>';
//        $testStr = array('0'=>'sky','1'=>'nd','2'=>'201508','3'=>'p5');
//        var_dump(json_encode($testStr,320));
//        echo '<br><br>';
//        var_dump(md5(json_encode($testStr,320)));
        //echo 'userid:'.$this->getRequest()->getParam('id',0);

//        $redis = new \Redis();
//        $result = $redis->connect('127.0.0.1', 6379);
//        if($result){
//            $redis->set('username','sky');
//            $userName = $redis->get('username');
//            if(!empty($userName)){
//                echo 'your name is '.$userName;
//            }else{
//                echo 'name is null';
//            }
//        }else{
//            echo 'redis service  fail';
//        }

//		$this->getView();


//根据字段last_name对数组$data进行降序排列
//       $last_names = array_column($data,'id');
//       $exchang    = array_column($data,NULL,'id');
////        array_multisort($last_names,SORT_DESC,$data);
//
//        var_dump($last_names,$exchang);
        $this->getView()->assign("content", "this is test");
        $this->getView()->display('index');

        exit;

	}
	
	public function testAction()
	{
//        $redis = Redis_Redis::getInstance();
//        $queryRedisKey = 'redis_test';
//        $value = $redis->get($queryRedisKey);
//        if ($value === false || $value == '') {
//            $flag = $redis->set($queryRedisKey, 'this is first redis info', 600);
//
//        }
        $fname=array("Bill","Steve","Mark");
        $age=array("60","56","31");

        $c=array_combine($age,$fname);
        print_r($c);
        var_dump($_SERVER['REQUEST_URI'],$_SERVER['REMOTE_ADDR']);
        exit;
        $dir = dirname(dirname(dirname(dirname(__FILE__))));
        $word = trim($_GET['word']);
        //$dir  = str_replace('\\','/',$dir);
        //var_dump($_SERVER);exit;
        $txt   = file($_SERVER['SERVER_NAME'].'/yafcode/text/test.txt');
        if(!empty($txt)){

            foreach ($txt as $key=>$val){
                $arr = explode(' ',$val);
                if($arr){
                    foreach ($arr as $k=>$v){
                        if($v ==$word){
                            echo $word.' in NO'.$key .' line '.' NO'.$k.' word<br>';
                        }
                    }
                }

            }

        }

        exit;
        $this->getView()->assign("content", "this is test");
        $this->getView()->display('index');
		//Dispatcher::getInstance()->disableView();


	}
	public static function getAllDir($dir){
	    if(!is_dir($dir)){
	        return $dir;
        }
        $dirs = scandir($dir);
	    if($dirs){
	        foreach ($dirs as $key=>$val){
	            if($val=='.' || $val=='..'){
                    self::getAllDir($dir.'/'.$val.'/');
                }
            }
        }
    }
}
