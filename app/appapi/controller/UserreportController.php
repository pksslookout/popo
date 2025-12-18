<?php
/**
 * 举报用户
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;
use cmf\lib\Upload;

class UserreportController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	public function index(){
		$data = $this->request->param();
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $uid=isset($data['uid']) ? $data['uid']: '';
        $touid=isset($data['touid']) ? $data['touid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $model=isset($data['model']) ? $data['model']: '';
        $version=isset($data['version']) ? $data['version']: '';
        $uid=(int)checkNull($uid);
        $touid=(int)checkNull($touid);
        $token=checkNull($token);
        $model=checkNull($model);
        $version=checkNull($version);

        if( !$uid || !$token || checkToken($uid,$token)==700 ){
            $reason=lang('您的登陆状态失效，请重新登陆！');
            $this->assign('reason', $reason);
            return $this->fetch(':error');
        }

        $list=Db::name('report_user_classify')->order('list_order desc')->select()->toArray();

        if(!in_array($lang,['zh_cn','en'])){
            $translate = get_language_translate_array('report_user_classify', 'name', $lang);
        }
        foreach ($list as $k=>$v){
            if($lang=='en'){
                $v['name'] = $v['name_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $v['name'] = $translate[$v['id']];
                    }
                }
            }
            $list[$k]=$v;
        }
        $this->assign("list",$list);
        $this->assign("lang",$lang);

        $this->assign("uid",$uid);
        $this->assign("touid",$touid);
        $this->assign("token",$token);
        $this->assign("version",$version);
        $this->assign("model",$model);
		return $this->fetch();
	    
	}

    function save(){
        $data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);

        if( !$uid || !$token || checkToken($uid,$token)==700 ){
            echo json_encode(array("status"=>400,'errormsg'=>lang('您的登陆状态失效，请重新登陆！')));
            exit;
        }

        $touid=isset($data['touid']) ? $data['touid']: '';
        $content=isset($data['content']) ? $data['content']: '';
        $thumb=isset($data['thumb']) ? $data['thumb']: '';
        $classifyid=isset($data['classifyid']) ? $data['classifyid']: '';

        $touid=checkNull($touid);
        $content=checkNull($content);
        $thumb=checkNull($thumb);
        $classifyid=checkNull($classifyid);
        if($content==''){
            echo json_encode(array("status"=>400,'errormsg'=>lang('反馈内容不能为空')));
            exit;
        }
        if($classifyid==''){
            echo json_encode(array("status"=>400,'errormsg'=>lang('联系方式不能为空')));
            exit;
        }

        $data2=[
            'uid'=>$uid,
            'touid'=>$touid,
            'content'=>$content,
            'image'=>$thumb,
            'classifyid'=>$classifyid,
            'addtime'=>time(),
        ];


        $result=Db::name("report_user")->insert($data2);
        if($result){
            echo json_encode(array("status"=>0,'msg'=>lang('提交成功')));
            exit;
        }else{
            echo json_encode(array("status"=>400,'errormsg'=>lang('提交失败')));
            exit;
        }

    }

    /* 图片上传 */
    public function upload(){
//        echo '{"ret":200,"data":{"url":"https:\/\/yunbaozhibo1024-1251420909.cos.ap-guangzhou.myqcloud.com\/default\/20250923\/ed1a659f1a29b7787b2090cef4ce3f29.png","filepath":"default\/20250923\/ed1a659f1a29b7787b2090cef4ce3f29.png"},"msg":""}';
//        exit;
        $file=isset($_FILES['image'])?$_FILES['image']:'';
        if($file){
            $name=$file['name'];
            $pathinfo = pathinfo($name);
            if(!isset($pathinfo['extension'])){
                $_FILES['file']['name']=$name.'.jpg';
            }
        }

        $configpri=getConfigPri();
        $cloudtype=$configpri['cloudtype'];
        if($cloudtype==1){
            $uploader = new Upload();
            $uploader->setFileType('image');
            $result = $uploader->upload();
            if ($result === false) {
                echo json_encode(array("ret"=>0,'file'=>'','msg'=>$uploader->getError()));
                exit;
            }
        }else{
            $files = $_FILES['image'];
            $result=cloudUploadFiles($files,2,$this->request->param());
            if ($result === false) {
                echo json_encode(array("ret"=>0,'file'=>'','msg'=>'上传失败'));
                exit;
            }
        }

        /* $result=[
            'filepath'    => $arrInfo["file_path"],
            "name"        => $arrInfo["filename"],
            'id'          => $strId,
            'preview_url' => cmf_get_root() . '/upload/' . $arrInfo["file_path"],
            'url'         => cmf_get_root() . '/upload/' . $arrInfo["file_path"],
        ]; */

        echo json_encode(array("ret"=>200,'data'=>array("url"=>$result['url'],"filepath"=>$result['filepath']),'msg'=>''));
        exit;
    }

}