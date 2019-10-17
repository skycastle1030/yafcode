<?php 

use Yaf\Dispatcher;
use Yaf\Controller_Abstract;
use Yaf\Application;
use Yaf\Registry;

class IndexController extends Controller_Abstract
{
	public function indexAction()
	{
	    phpinfo();
        $arrConfig = Application::app()->getConfig();
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
       var_dump(rand(1,10000));

        exit;

	}
	
	public function testAction()
	{
        echo '1111Hello!world';
        $redis = Redis_Redis::getInstance();
        $queryRedisKey = 'redis_test';
        $value = $redis->get($queryRedisKey);
        if ($value === false || $value == '') {
            $flag = $redis->set($queryRedisKey, 'this is first redis info', 600);

        }

		Dispatcher::getInstance()->disableView();


	}
}
