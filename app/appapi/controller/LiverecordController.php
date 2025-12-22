<?php
/**
 * 直播回放
 */

namespace app\appapi\controller;


use think\Controller;
use think\Db;

class LiverecordController extends Controller
{

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }

    function index(){
        $data = $this->request->param();
        $user=isset($data['user']) ? $data['user']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $user=checkNull($user);
        if(empty($user)){
            echo '用户不存在！';
            exit();
        }

        $uid=Db::name('user')->where(["user_login"=>$user])->value('id');
        if(empty($uid)){
            echo '用户不存在！';
            exit();
        }

        $userinfo=getUserInfo($uid);

        $this->assign("userinfo",$userinfo);

        $list=Db::name("live_record")->where(["uid"=>$uid])->order("starttime desc")->limit(0,50)->select()->toArray();
        foreach($list as $k=>$v){

            $list[$k]['starttime_ymd']=date('Y-m-d',$v['starttime']);
            $list[$k]['starttime']=date('H:i',$v['starttime']);
            $list[$k]['endtime']=date('H:i',$v['endtime']);
        }

        $this->assign("list",$list);

        return $this->fetch();

    }

}