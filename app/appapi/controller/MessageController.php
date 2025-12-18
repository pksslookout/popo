<?php
/**
 * 关于我们
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;
use cmf\lib\Upload;

class MessageController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	public function msginfo(){
		$data = $this->request->param();
        $id=isset($data['id']) ? $data['id']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';

        $info=Db::name('official')->where(["id"=>$id])->find();

        $info['content']=htmlspecialchars_decode($info['content']);
        $this->assign("info",$info);
        $this->assign("lang",$lang);
		return $this->fetch();
	    
	}

}