<?php
/**
 * 关于我们
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use cmf\lib\Upload;

class AboutController extends HomebaseController {
	
	public function index(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $version=isset($data['version']) ? $data['version']: '1.0.0';
        $ios=isset($data['ios']) ? $data['ios']: '0';
        $device=isset($data['device']) ? $data['device']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=lang('您的登陆状态失效，请重新登陆！');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}

        $this->assign("lang",$lang);
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("version",$version);
		$this->assign("device",$device);

		return $this->fetch();
	    
	}

}