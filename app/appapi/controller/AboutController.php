<?php
/**
 * 关于我们
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;
use cmf\lib\Upload;

class AboutController extends Controller {

    protected function initialize()
    {
        parent::initialize();
        /* redis缓存开启 */
        connectionRedis();
    }
	
	public function index(){
		$data = $this->request->param();
        $user=isset($data['user']) ? $data['user']: '';
        if(empty($user)){
            echo '用户不存在！';
            exit();
        }
        $user=checkNull($user);
        $device=isset($data['device']) ? $data['device']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';

        $configPri = getConfigPub();
        $this->assign("lang",$lang);
		$this->assign("version",$configPri['apk_ver']);
		$this->assign("device",$device);
		$this->assign("user",$user);

		return $this->fetch();
	    
	}

}