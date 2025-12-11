<?php
/**
 * 分销
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class AgentshareController extends HomebaseController {
	
	function index(){       
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $uid=(int)checkNull($uid);
        $userinfo=getUserInfo($uid);
        $code=Db::name('agent_code')->where(["uid"=>$uid])->value('code');

        if(!$code){
            $code=createCode();
            $ifok=Db::name('agent_code')->where(["uid"=>$uid])->update(array("code"=>$code));
            if(!$ifok){
                Db::name('agent_code')->insert(array('uid'=>$uid,"code"=>$code));
            }

        }

        $code_a=str_split($code);

        $this->assign("code",$code);
        $this->assign("openinstall_switch",$this->configpri['openinstall_switch']);
        $this->assign("code_a",$code_a);
		$this->assign("lang",$lang);
		$this->assign("uid",$uid);
        $this->assign("userinfo",$userinfo);

		return $this->fetch();
	    
	}

}