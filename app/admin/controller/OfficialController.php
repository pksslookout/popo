<?php

/**
 * 官方通知
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class OfficialController extends AdminbaseController {

    function log(){
        $data = $this->request->param();
        $map=[];

        $title=isset($data['title']) ? $data['title']: '';
        if($title!=''){
            $map[]=['title','like',"%".$title."%"];
        }	
			
    	$lists = Db::name("official")
                ->where($map)
                ->order("pushtime DESC")
                ->paginate(20);

        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        

    	return $this->fetch();
    }
    
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('official')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        
        $action="删除官方通知：{$id}";
        setAdminLog($action);
        
        $this->success("删除成功！",url("official/log"));
    }


    function add(){
        return $this->fetch();
    }

    function addPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['content']=isset($data['content'])?$data['content']:'';
            $data['type']=trim($data['type']);
            $data['url']=isset($data['url'])?trim($data['url']):'';
            $data['title']=trim($data['title']);
            $data['introduction']=trim($data['introduction']);
            $data['push_user']=trim($data['push_user']);
            $data['push_ip']=trim(get_client_ip());
            $title=$data['title'];
            $introduction=$data['introduction'];

            if($title==""){
                $this->error("消息标题不能为空");
            }

            if($introduction==""){
                $this->error("简介不能为空");
            }

            $isexit=DB::name('official')->where(["title"=>$title])->find();
            if($isexit){
                $this->error('该消息标题已存在');
            }

            $data['addtime']=time();
            $data['pushtime']=time();

            $id = DB::name('official')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            if($data['type'] == 1){
                $data = [];
                $data['id'] = $id;
                $data['url'] = '/appapi/message/msginfo.html&id='.$id;
                $rs = DB::name('official')->update($data);
                if($rs===false){
                    $this->error("修改失败！");
                }
            }
            $action="添加消息：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
    }

    function push(){

        $id = $this->request->param('id', 0, 'intval');
        $push_user = $this->request->param('push_user', '');

        $rs = DB::name('official')->where("id={$id}")->find();
        if(!$rs){

            $rs=array('code'=>1,'msg'=>'推送失败','info'=>array());
            echo json_encode($rs);
            exit();
        }

        $title = $rs['title'];
        $url = $rs['url'];
        $introduction = $rs['introduction'];
        $configpri=getConfigPri();
        if($configpri['typepush_switch']==1) {
            /* 腾讯IM 推送服务 Push */
            $method_name = 'timpush/push';
            $identifier = 'administrator';
            $random=random_int(0,4294967295);
            $post = [
                'From_Account' => $identifier,
                'MsgRandom' => $random,
                'OfflinePushInfo' => [
                    'PushFlag' => 0,
                    'Title' => $title,
                    'Desc' => $introduction,
                    'Ext' => '{"entity":{"url":"'.$url.'","key2":"value2"}}',
                ]
            ];
            $post = json_encode($post);
            $response=txImPostParam($identifier,$method_name,$post);
            $rs=json_decode($response,true);
            if($rs['ActionStatus']=='OK'){
                $data = [];
                $data['id'] = $id;
                $data['pushtime'] = time();
                $data['push_ip'] = get_client_ip();
                $data['push_user'] = $push_user;
                $data['is_status'] = 1;
                DB::name('official')->update($data);
                $rs = array('code' => 0, 'msg' => '', 'info' => array());
                echo json_encode($rs);
                exit();
            }else{
                $rs = array('code' => 1, 'msg' => "推送失败！(".$rs['ErrorInfo'].")", 'info' => array());
                echo json_encode($rs);
                exit();
            }
        }else {
            /* 极光推送 */
            require_once CMF_ROOT.'sdk/JPush/autoload.php';
            $app_key = $configpri['jpush_key'];
            $master_secret = $configpri['jpush_secret'];

            if (!$app_key || !$master_secret) {
                $rs = array('code' => 1, 'msg' => '推送失败', 'info' => array());
                echo json_encode($rs);
                exit();
            }
            if ($app_key && $master_secret) {
                // 初始化
                $client = new \JPush\Client($app_key, $master_secret, null);
                file_put_contents(CMF_ROOT . 'data/jpush.txt', date('y-m-d h:i:s') . '提交参数信息 设备名client2:' . json_encode($client) . "\r\n", FILE_APPEND);
//				file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名client:'.$client."\r\n",FILE_APPEND);
                $anthorinfo = array();
                $apns_production = false;
                if ($configpri['jpush_sandbox']) {
                    $apns_production = true;
                }

                try {
                    $result = $client->push()
                        ->setPlatform('all')
                        ->addAllAudience()
                        ->setNotificationAlert($title)
                        ->iosNotification($title, array(
                            'sound' => 'sound.caf',
                            'category' => 'jiguang',
                            'extras' => array(
                                'type' => '1',
                                'url' => $url,
                                'userinfo' => $anthorinfo
                            ),
                        ))
                        ->androidNotification($introduction, array(
                            'title' => $title,
                            'extras' => array(
                                'type' => '1',
                                'url' => $url,
                                'userinfo' => $anthorinfo
                            ),
                        ))
                        ->options(array(
                            'sendno' => 100,
                            'time_to_live' => 0,
                            'apns_production' => $apns_production,
                        ))
                        ->send();
                    if ($result['code'] == 0) {
                        $data = [];
                        $data['id'] = $id;
                        $data['pushtime'] = time();
                        $data['push_ip'] = get_client_ip();
                        $data['push_user'] = $push_user;
                        $data['is_status'] = 1;
                        DB::name('official')->update($data);
                        $rs = array('code' => 0, 'msg' => '', 'info' => array());
                        echo json_encode($rs);
                        exit();
                    }
                } catch (Exception $e) {
                    file_put_contents(CMF_ROOT . 'data/jpush.txt', date('y-m-d h:i:s') . '提交参数信息 设备名:' . json_encode($alias) . "\r\n", FILE_APPEND);
                    file_put_contents(CMF_ROOT . 'data/jpush.txt', date('y-m-d h:i:s') . '提交参数信息:' . $e . "\r\n", FILE_APPEND);
                }

                $rs = array('code' => 1, 'msg' => '推送失败', 'info' => array());
                echo json_encode($rs);
                exit();
            }
        }
    }
}
