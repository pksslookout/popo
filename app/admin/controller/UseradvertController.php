<?php

/**
 * 广告管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use cmf\lib\Upload;

class UseradvertController extends AdminbaseController {

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
        $map[]=['is_admin','=',0];
        $map[]=['status','=',1];
        $map[]=['isdel','=',0];

    	$lists = Db::name("video")
                ->where($map)
                ->order("id DESC")
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

    /* 上下架 */
    public function setStatus(){
        $id = $this->request->param('id', 0, 'intval');
        $isdel = $this->request->param('isdel', 0, 'intval');
        if($id){

            $result=DB::name("video")->where(['id'=>$id])->update(['isdel'=>$isdel]);
            if($result){

                $action="上下架用户广告视频：用户广告视频ID({$id}),状态({$isdel})";
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

        $this->success("删除成功！",url("useradvert/index"));
    }
    
}
