<?php
/**
 * 关于我们
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Controller;
use think\Db;
use cmf\lib\Upload;

class ServiceController extends Controller {
	
	public function index(){
		$data = $this->request->param();
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $this->assign("lang",$lang);
		return $this->fetch();
	    
	}

}