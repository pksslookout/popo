<?php
/**
 * 直播回放
 */

namespace app\appapi\controller;


use think\Controller;
use think\Db;

class LiveauthorityController extends Controller
{

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }

    function index(){
        $data = $this->request->param();
        $user=isset($data['user']) ? $data['user']: '';
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


        $configPri = getConfigPri();

        $fans = Db::name("user_attention")
            ->where("touid='{$uid}' and status=1")
            ->count();
        if($fans>=$configPri['live_fans_number']){
            $fans = 1;
        }
        $video = Db::name("video")
            ->where(["uid"=>$uid])
            ->count();
        if($video>=$configPri['live_video_number']){
            $video = 1;
        }
        $this->assign("fans",$fans);
        $this->assign("video",$video);

        return $this->fetch();

    }

}