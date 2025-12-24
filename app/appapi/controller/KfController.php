<?php
/**
 * 客服留言
 */
namespace app\appapi\controller;

use think\Controller;
use think\Db;
use cmf\lib\Upload;

class KfController extends Controller{

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	function index(){
        
        $data = $this->request->param();
        $model=isset($data['model']) ? $data['model']: '';
        $version=isset($data['version']) ? $data['version']: '';
        $model=checkNull($model);
        $version=checkNull($version);

        
        $this->assign("version",$version);
        $this->assign("model",$model);
        return $this->fetch();
	}
	
	function save(){
        echo json_encode(array("status"=>0,'msg'=>''));
        exit;
        $data = $this->request->param();
        $user=isset($data['user']) ? $data['user']: '';
        if(empty($user)){
            echo '用户不存在！';
            exit();
        }
        $user=checkNull($user);
        $uid=Db::name('user')->where(["user_login"=>$user])->value('id');
        if(empty($uid)){
            echo '用户不存在！';
            exit();
        }
        
        $version=isset($data['version']) ? $data['version']: '';
        $model=isset($data['model']) ? $data['model']: '';
        $content=isset($data['content']) ? $data['content']: '';
        $thumb=isset($data['thumb']) ? $data['thumb']: '';
        $phone=isset($data['phone']) ? $data['phone']: '';

        $version=checkNull($version);
        $model=checkNull($model);
        $content=checkNull($content);
        $thumb=checkNull($thumb);
        $phone=checkNull($phone);

        $data2=[
            'uid'=>$uid,
            'version'=>$version,
            'model'=>$model,
            'content'=>$content,
            'thumb'=>$thumb,
            'phone'=>$phone,
            'addtime'=>time(),
        ];
        

		$result=Db::name("feedback")->insert($data2);
		if($result){
            echo json_encode(array("status"=>0,'msg'=>''));
            exit;
		}else{
            echo json_encode(array("status"=>400,'errormsg'=>lang('提交失败')));
            exit;
		}
	
	}
}