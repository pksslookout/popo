<?php
/**
 * 关于我们
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;
use cmf\lib\Upload;

class ServiceController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	public function index(){
		$data = $this->request->param();
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $this->assign("lang",$lang);
		return $this->fetch();
	    
	}

}