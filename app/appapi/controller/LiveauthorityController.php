<?php
/**
 * 直播回放
 */

namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class LiveauthorityController extends HomebaseController
{

    function index(){
        $data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $reason=lang('您的登陆状态失效，请重新登陆！');
            $this->assign('reason', $reason);
            return $this->fetch(':error');
        }

        $this->assign("uid",$uid);
        $this->assign("token",$token);

        $fans = Db::name("user_attention")
            ->where("touid='{$uid}' and status=1")
            ->count();
        $video = Db::name("video")
            ->where(["uid"=>$uid])
            ->count();
        $this->assign("fans",$fans);
        $this->assign("video",$video);

        return $this->fetch();

    }

}