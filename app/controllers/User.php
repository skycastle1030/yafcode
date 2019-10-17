<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 11:04
 */
use Yaf\Dispatcher;
use Yaf\Controller_Abstract;
use Yaf\Application;
use Yaf\Request\Simple;

class UserController extends Controller_Abstract
{
    public function indexAction(){

//       var_dump(date('w'),date('W'));
//       var_dump($this->setTime(6000));

        $arr   = array('0'=>1,'1'=>12,'2'=>4,'3'=>5,'4'=>9,'5'=>7,'6'=>11,'7'=>100,'8'=>10);
        $data  = array('10'=>65,'5'=>70,'9'=>80,'10'=>9,'11'=>8);
        $data2 = array('a','b','c','y','x','z','j');

//根据字段last_name对数组$data进行降序排列
//       $last_names = array_column($data,'id');
//       $exchang    = array_column($data,NULL,'id');
////        array_multisort($last_names,SORT_DESC,$data);
//
//        var_dump($last_names,$exchang);
        rsort($arr);
        var_dump($arr);
        var_dump(array_slice($data,0,3));
       // var_dump($data,$data2,array_unique(array_merge_recursive($data,$data2)));

        exit;
    }
    public function setTime($time){
       return gmdate('H小时i分钟s秒',$time);
//        $setTime = '00小时00分钟';
//        if($time>0){
//            $hour = floor($setTime/(60*60));
//            var_dump($hour);
//            if($hour>0) {
//                $minute = floor(($setTime % (60 * 60)) / 60);
//                var_dump($minute);
//                $setTime = $hour . '小时' . $minute . '分钟';
//                if( floor(($setTime % (60 * 60)) %60)>0){
//
//                    $setTime = $hour . '小时' . $minute . '分钟'. floor(($setTime % 60 * 60) %60).'秒';
//                }
//            }else{
//                $minute = floor($setTime/60);
//                if($minute>0) {
//                    $setTime = $minute . '分钟';
//                    if(floor($setTime%60)>0){
//                        $setTime = $minute . '分钟'.floor($setTime%60).'秒';
//                    }
//                }else{
//                    $setTime = $setTime . '秒';
//                }
//
//            }
//
//        }
//        return $setTime;

    }
//    public function init(){
//    $params = array(
//            'name' => 'value',
//            );
//
//    $this->getView()->assign($params)->assign("foo", "bar");
//    }
//    public function indexAction()
//    {
////        $config = array(
////            "ap" => array(
////                "directory" => "/usr/local/www/ap",
////            ),
////        );
////        $app = new Application($config);
////        print_r($app->getConfig('application'));
//
//        var_dump(Yaf\Registry::has('user'));
//        Yaf\Registry::set('user', 'sky');
//        var_dump(Yaf\Registry::has('user'));
//        /* 之后可以在任何地方获取到 */
//        $config=Yaf\Registry::get("user");
//        var_dump($config);
//        echo $this->getView()->assign('content','this is new')->display( "index/index.phtml");
//        echo "current Controller:" . $this->getRequest()->getControllerName();
//        echo "current Action:" . $this->getRequest()->getActionName();
//        var_dump($this->getRequest()->getParams());
//        echo $this->getRequest()->getMethod();
//        $config = array(
//            "ap" => array(
//                "directory" => "/usr/local/www/ap",
//            ),
//        );
//        $app = new Application($config);
//        $app->execute("main");
//        $request = new Simple();
//        $exception = $this->getRequest()->getException();
//        print_r($exception);
//        try {
//            throw $exception;
//        } catch (Yaf\Exception\LoadFailed $e) {
//            //加载失败
//            echo 'fail';
//        } catch (Yaf_Exception $e) {
//            //其他错误
//            echo 'other';
//        }
//
//        exit;
//    }



}