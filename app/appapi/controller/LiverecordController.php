<?php
/**
 * 直播回放
 */

namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Controller;
use think\Db;

class LiverecordController extends Controller
{

    function index(){
        $data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $touid=isset($data['touid']) ? $data['touid']: '';
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

        $userinfo=getUserInfo($touid);

        $this->assign("userinfo",$userinfo);

        $list=Db::name("live_record")->where(["uid"=>$touid])->order("starttime desc")->limit(0,5000)->select()->toArray();
        foreach($list as $k=>$v){

            $list[$k]['starttime_ymd']=date('Y-m-d',$v['starttime']);
            $list[$k]['starttime']=date('H:i',$v['starttime']);
            $list[$k]['endtime']=date('H:i',$v['endtime']);
        }

        $this->assign("list",$list);

        return $this->fetch();

    }

}