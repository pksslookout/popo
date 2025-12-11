<?php

/**
 * 广告管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use cmf\lib\Upload;

class AdvertController extends AdminbaseController {

    function add(){
        $data = $this->request->param();

        $map=[];
        $map[]=['isadvert','=',1];

        $user_lists = Db::name("user")
            ->where($map)
            ->order("id DESC")
            ->paginate(200);


        $user_lists->appends($data);
        $page = $user_lists->render();

        $this->assign("page", $page);

        $this->assign('user_lists', $user_lists);

        return $this->fetch();
    }

    function edit(){
        $id   = $this->request->param('id', 0, 'intval');

        $data=Db::name('video')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }
        $data['thumb_url']=get_upload_path($data['thumb']);
        $data['href_url']=get_upload_path($data['href']);
        $userinfo=getUserInfo($data['uid']);
        $data['user_nicename']=$userinfo['user_nicename'];

        $this->assign('data', $data);
        return $this->fetch();
    }

    function editPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();



            $uid = $data['uid'];

            if ($uid == "") {
                $this->error("请填写用户ID");
            }

            $isexist = DB::name("user")->where("id={$uid} and user_type=2")->value('id');
            if (!$isexist) {
                $this->error("该用户不存在");
            }

            $title=$data['title'];
            if($title==""){
                $this->error("请填写标题");
            }

            $thumb=$data['thumb'];
            if($thumb==""){
                $this->error("请上传图片");
            }


            if(isset($data['video_type'])){
                if($data['video_type'] == 2){
                    $data['href']=$data['file'];
                }
            }

            if($data['href'] == ''){
                unset($data['href']);
            }

            unset($data['video_type']);
            unset($data['file']);

            //计算封面尺寸比例
            $anyway='1.1';

            $configpub=getConfigPub(); //获取公共配置信息

            $thumb_url=get_upload_path($thumb);

            $refer=$configpub['site'];
            $option=array('http'=>array('header'=>"Referer: {$refer}"));
            $context=stream_context_create($option);//创建资源流上下文
            $file_contents = @file_get_contents($thumb_url,false, $context);//将整个文件读入一个字符串
            if($file_contents){
                $thumb_size = getimagesizefromstring($file_contents);//从字符串中获取图像尺寸信息
                if($thumb_size){
                    $thumb_width=$thumb_size[0];  //封面-宽
                    $thumb_height=$thumb_size[1];  //封面-高

                    $anyway=round($thumb_height/$thumb_width);
                }

                $data['anyway']=$anyway;
            }else{
                $data['anyway']='0';
            }
            $data['thumb_s']=$data['thumb'];

            $data['addtime']=time();
            $data['is_ad']=1;
            $data['is_admin']=1;
            $data['ad_endtime']=strtotime(trim($data['ad_endtime']));

            $rs = DB::name('video')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }

            $action="修改视频广告：{$data['id']}";
            setAdminLog($action);

            $this->success("修改成功！");

        }
    }

    function addPost(){

        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $uid = $data['uid'];

            if ($uid == "") {
                $this->error("请填写用户ID");
            }

            $isexist = DB::name("user")->where("id={$uid} and user_type=2")->value('id');
            if (!$isexist) {
                $this->error("该用户不存在");
            }

            $title=$data['title'];
            if($title==""){
                $this->error("请填写标题");
            }

            $thumb=$data['thumb'];
            if($thumb==""){
                $this->error("请上传图片");
            }

            if($data['video_type'] == 2){
                $file=isset($_FILES['file'])?$_FILES['file']:'';
                if(!$file){
                    $this->error("请上传视频");
                }

                $configpri=getConfigPri();
                $cloudtype=$configpri['cloudtype'];
                if($cloudtype==1) {
                    $res = $this->upload();
                    if($res['ret']==0){
                        $this->error($res['msg']);
                    }
                }else{
                    $res = cloudUploadFiles($file,2,$this->request->param());
                    if($res===false){
                        $this->error('上传视频失败');
                    }
                    $res['data'] = $res;
                    $res['data']['url'] = $res['data']['filepath'];
                }
                $data['href']=$res['data']['url'];
            }

            unset($data['video_type']);
            unset($data['file']);

            $href=$data['href'];
            if($href==""){
                $this->error("请上传视频");
            }

            $data['href_w']=$data['href'];


            //计算封面尺寸比例
            $anyway='1.1';

            $configpub=getConfigPub(); //获取公共配置信息

            $thumb_url=get_upload_path($thumb);

            $refer=$configpub['site'];
            $option=array('http'=>array('header'=>"Referer: {$refer}"));
            $context=stream_context_create($option);//创建资源流上下文
            $file_contents = @file_get_contents($thumb_url,false, $context);//将整个文件读入一个字符串
            if($file_contents){
                $thumb_size = getimagesizefromstring($file_contents);//从字符串中获取图像尺寸信息
                if($thumb_size){
                    $thumb_width=$thumb_size[0];  //封面-宽
                    $thumb_height=$thumb_size[1];  //封面-高

                    $anyway=round($thumb_height/$thumb_width);
                }

                $data['anyway']=$anyway;
            }else{
                $data['anyway']='0';
            }
            $data['thumb_s']=$data['thumb'];

            $data['addtime']=time();
            $data['is_ad']=1;
            $data['is_admin']=1;
            $data['status']=1;
            $data['ad_endtime']=strtotime(trim($data['ad_endtime']));

            $id = DB::name('video')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }

            $action="添加视频广告：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
    }

    //排序
    public function listOrder() {

        $model = DB::name('video');
        parent::orderNos($model);


        $action="更新广告排序";
        setAdminLog($action);

        $this->success("排序更新成功！");

    }

    function index(){
        $data = $this->request->param();
        $map=[];

        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['uid|id','=',$keyword];
        }

        $title=isset($data['title']) ? $data['title']: '';
        if($title!=''){
            $map[]=['title','like',"%".$title."%"];
        }

//        $user_nicename=isset($data['user_nicename']) ? $data['user_nicename']: '';
//        if($user_nicename!=''){
//            $map[]=['user_nicename','like',"%".$user_nicename."%"];
//        }
        $map[]=['is_ad','=',1];
        $map[]=['is_admin','=',1];
        $map[]=['status','=',1];
        $map[]=['isdel','=',0];

    	$lists = Db::name("video")
                ->where($map)
                ->order("orderno DESC")
                ->paginate(20);

        
        $lists->appends($data);
        $page = $lists->render();

        $lists->each(function($v,$k){
            $v['userinfo']=getUserInfo($v['uid']);
            $v['thumb']=get_upload_path($v['thumb']);
            $v['href']=get_upload_path($v['href']);
            return $v;
        });

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        

    	return $this->fetch();
    }

    function lowervideo(){
        $data = $this->request->param();
        $map=[];

        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['uid|id','=',$keyword];
        }

        $title=isset($data['title']) ? $data['title']: '';
        if($title!=''){
            $map[]=['title','like',"%".$title."%"];
        }

//        $user_nicename=isset($data['user_nicename']) ? $data['user_nicename']: '';
//        if($user_nicename!=''){
//            $map[]=['user_nicename','like',"%".$user_nicename."%"];
//        }
        $map[]=['is_ad','=',1];
        $map[]=['is_admin','=',1];
        $map[]=['status','=',1];
        $map[]=['isdel','=',1];

    	$lists = Db::name("video")
                ->where($map)
                ->order("orderno DESC")
                ->paginate(20);


        $lists->appends($data);
        $page = $lists->render();

        $lists->each(function($v,$k){
            $v['userinfo']=getUserInfo($v['uid']);
            $v['thumb']=get_upload_path($v['thumb']);
            $v['href']=get_upload_path($v['href']);
            return $v;
        });

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);


    	return $this->fetch();
    }
    
    function del(){

        $id = $this->request->param('id', 0, 'intval');

        $rs = DB::name('video')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }

        DB::name("video_black")->where("videoid={$id}")->delete();	 //删除视频拉黑
        DB::name("video_comments")->where("videoid={$id}")->delete();	 //删除视频评论
        DB::name("video_like")->where("videoid={$id}")->delete();	 //删除视频喜欢
        DB::name("video_report")->where("videoid={$id}")->delete();	 //删除视频举报
        DB::name("video_comments_like")->where("videoid={$id}")->delete(); //删除视频评论喜欢

        $action="删除广告视频：{$id}";
        setAdminLog($action);
        
        $this->success("删除成功！",url("advert/index"));
    }

    function del2(){

        $id = $this->request->param('id', 0, 'intval');

        $rs = DB::name('video')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }

        DB::name("video_black")->where("videoid={$id}")->delete();	 //删除视频拉黑
        DB::name("video_comments")->where("videoid={$id}")->delete();	 //删除视频评论
        DB::name("video_like")->where("videoid={$id}")->delete();	 //删除视频喜欢
        DB::name("video_report")->where("videoid={$id}")->delete();	 //删除视频举报
        DB::name("video_comments_like")->where("videoid={$id}")->delete(); //删除视频评论喜欢

        $action="删除广告视频：{$id}";
        setAdminLog($action);

        $this->success("删除成功！",url("advert/lowervideo"));
    }

    protected function upload()
    {

        $uploader = new Upload();
        $uploader->setFileType('video');
        $result = $uploader->upload();

        if ($result === false) {
            return array("ret"=>0,'file'=>'','msg'=>$uploader->getError());
        }

        /* $result=[
            'filepath'    => $arrInfo["file_path"],
            "name"        => $arrInfo["filename"],
            'id'          => $strId,
            'preview_url' => cmf_get_root() . '/upload/' . $arrInfo["file_path"],
            'url'         => cmf_get_root() . '/upload/' . $arrInfo["file_path"],
        ]; */

        return array("ret"=>200,'data'=>array("url"=>$result['filepath']),'msg'=>'');

    }

    /* 上下架 */
    public function setStatus(){
        $id = $this->request->param('id', 0, 'intval');
        $isdel = $this->request->param('isdel', 0, 'intval');
        $reason = $this->request->param('reason');
        if($reason==''){
            $reason='';
        }
        if($id){

            $result=DB::name("video")->where(['id'=>$id])->update(['isdel'=>$isdel,'xiajia_reason'=>$reason]);
            if($result){

                $action="上下架视频：视频ID({$id}),状态({$isdel})";
                setAdminLog($action);

                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->error('数据传入失败！');
        }

    }

    public function  see(){

        $id   = $this->request->param('id', 0, 'intval');

        $data=Db::name('video')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }

        $data['href']=get_upload_path($data['href']);

        $this->assign('data', $data);
        return $this->fetch();

    }
    
}
