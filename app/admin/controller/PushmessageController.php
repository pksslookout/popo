<?php

/**
 * 推送管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class PushmessageController extends AdminbaseController {

    function push(){

        require_once CMF_ROOT.'sdk/JPush/autoload.php';

        // 初始化
        $client = new \JPush\Client($app_key, $master_secret,null);
        file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名client2:'.json_encode($client)."\r\n",FILE_APPEND);
//				file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名client:'.$client."\r\n",FILE_APPEND);
        $anthorinfo=array();

        $map=array();

        if($touid!=''){
            $uids=preg_split('/,|，/',$touid);
            $map[]  =['uid','in',$uids];
        }

        $pushids=DB::name("user_pushid")
            ->field("pushid")
            ->where($map)
            ->select()
            ->toArray();

        $pushids=array_column($pushids,'pushid');
        $pushids=array_filter($pushids);

        $nums=count($pushids);

        $apns_production=false;
        if($configpri['jpush_sandbox']){
            $apns_production=true;
        }
        $title=$content;
        for($i=0;$i<$nums;){
            $alias=array_slice($pushids,$i,900);
            $i+=900;
            try{
                $result = $client->push()
                    ->setPlatform('all')
                    ->addRegistrationId($alias)
                    ->setNotificationAlert($title)
                    ->iosNotification($title, array(
                        'sound' => 'sound.caf',
                        'category' => 'jiguang',
                        'extras' => array(
                            'type' => '1',
                            'userinfo' => $anthorinfo
                        ),
                    ))
                    ->androidNotification($title, array(
                        'title' => $title,
                        'extras' => array(
                            'type' => '1',
                            'userinfo' => $anthorinfo
                        ),
                    ))
                    ->options(array(
                        'sendno' => 100,
                        'time_to_live' => 0,
                        'apns_production' =>  $apns_production,
                    ))
                    ->send();
                if($result['code']==0){
                    $issuccess=1;
                }else{
                    $error=$result['msg'];
                }
            } catch (Exception $e) {
                file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($alias)."\r\n",FILE_APPEND);
                file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND);
            }
        }
    }
    
}
