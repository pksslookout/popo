<?php

/**
 * 直播举报
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AuthorcenterController extends AdminbaseController {
    function index(){
        $data = $this->request->param();
        $map=[];
        
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }
        
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            $lianguid=getLianguser($uid);
            if($lianguid){
                $map[]=['uid',['=',$uid],['in',$lianguid],'or'];
            }else{
                $map[]=['uid','=',$uid];
            }
        }	
			

    	$lists = Db::name("author_center")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);
        
        $lists->each(function($v,$k){
            $v['thumb']=get_upload_path($v['thumb']);
            return $v;
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
    	$this->assign("classify", $this->getClassify());

    	$this->assign("status", $this->getStatus());

    	return $this->fetch();
    }
    
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('author_center')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        delete_language_translate('author_center', 'title', $id);
        $action="删除创作者活动：{$id}";
        setAdminLog($action);
        
        $this->success("删除成功！",url("report/index"));								  
    }

    protected function getClassify($k=''){
        $map[]=['is_status','=',1];
        $list=Db::name("author_center_classify")
            ->where($map)
            ->order("list_order asc")
            ->column('name','id');

        if($k==''){
            return $list;
        }
        return isset($list[$k])?$list[$k]:'';
    }

    protected function getStatus($k=''){
        $status=[
            '0'=>'不显示',
            '1'=>'显示',
        ];

        if($k==''){
            return $status;
        }
        return $status[$k];
    }

    function classify(){

        $data = $this->request->param();
        $map=[];
        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['name','like',"%".$keyword."%"];
        }

        $lists = Db::name("author_center_classify")
            ->where($map)
            ->order("list_order asc")
            ->paginate(20);

        $lists->appends($data);
        $page = $lists->render();

        $this->assign('lists', $lists);

        $this->assign("page", $page);

        return $this->fetch();
    }

    //排序
    public function listOrder() {

        $model = DB::name('author_center_classify');
        parent::listOrders($model);


        $action="更新用户举报类型排序";
        setAdminLog($action);

        $this->success("排序更新成功！");

    }

    function catDel(){

        $id = $this->request->param('id', 0, 'intval');

        $rs = DB::name('author_center_classify')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        delete_language_translate('author_center_classify', 'name', $id);

        $action="删除创作者分类：{$id}";
        setAdminLog($action);

        $this->success("删除成功！",url("authorcenter/classify"));

    }


    function cat_add(){
        $this->assign('list_language', get_language_all());
        return $this->fetch();
    }

    function catAddPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['name']=trim($data['name']);
            $data['name_en']=trim($data['name_en']);
            $name=$data['name'];
            $name_en=$data['name_en'];

            if($name==""){
                $this->error("创作者分类名称不能为空");
            }

            if($name_en==""){
                $this->error("Creator Category Name不能为空");
            }

            $isexit=DB::name('author_center_classify')->where(["name"=>$name])->find();
            if($isexit){
                $this->error('该创作者分类名称已存在');
            }

            $data['addtime']=time();

            // 新增多国语言
            $language = $data['language'];
            unset($data['language']);

            $id = DB::name('author_center_classify')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('author_center_classify', 'name', $id, $language);

            $action="添加创作者分类：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
    }

    function cat_edit(){
        $id   = $this->request->param('id', 0, 'intval');

        $data=Db::name('author_center_classify')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }

        $this->assign('data', $data);
        $this->assign('list_language_translate', get_language_translate('author_center_classify', 'name', $id));
        return $this->fetch();
    }

    function catEditPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();


            $data['name']=trim($data['name']);
            $data['name_en']=trim($data['name_en']);
            $name=$data['name'];
            $name_en=$data['name_en'];

            $id=$data['id'];

            if($name==""){
                $this->error("创作者分类名称不能为空");
            }

            if($name_en==""){
                $this->error("Creator Category Name不能为空");
            }

            $isexit=DB::name('author_center_classify')->where([['id','<>',$id],['name','=',$name]])->find();
            if($isexit){
                $this->error('该创作者分类名称已存在');
            }

            // 修改多国语言
            $language = $data['language'];
            unset($data['language']);

            $rs = DB::name('author_center_classify')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }
            update_language_translate('author_center_classify', 'name', $id, $language);

            $action="修改创作者分类：{$id}";
            setAdminLog($action);

            $this->success("修改成功！");
        }
    }


    function add(){
        $this->assign('list_language', get_language_all());
        $this->assign("classify", $this->getClassify());
        return $this->fetch();
    }

    function addPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['title']=trim($data['title']);
            $data['title_en']=trim($data['title_en']);
            $data['classifyid']=trim($data['classifyid']);
            $data['thumb']=trim($data['thumb']);
            $data['active_start_time']=strtotime(trim($data['active_start_time']));
            $data['active_end_time']=strtotime(trim($data['active_end_time']));
            $data['submission_start_time']=strtotime(trim($data['submission_start_time']));
            $data['submission_end_time']=strtotime(trim($data['submission_end_time']));
            $data['activity_play']=trim($data['activity_play']);
            $data['related_topics']=implode(', ', $data['related_topics']);
            $data['activity_reward']=trim($data['activity_reward']);
            $title=$data['title'];
            $title_en=$data['title_en'];

            if($title==""){
                $this->error("标题不能为空");
            }

            if($title_en==""){
                $this->error("Activity Title不能为空");
            }

            $isexit=DB::name('author_center')->where(["title"=>$title])->find();
            if($isexit){
                $this->error('该创作者活动标题已存在');
            }

            $data['addtime']=time();
            $data['updatetime']=time();
            // 新增多国语言
            $language = $data['language'];
            unset($data['language']);
            $id = DB::name('author_center')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('author_center', 'title', $id, $language);

            $action="添加创作者活动：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
    }

    function edit(){
        $id   = $this->request->param('id', 0, 'intval');

        $data=Db::name('author_center')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }
        $data['related_topics'] = explode(',', $data['related_topics']);
        $data['thumb'] = get_upload_path($data['thumb']);
        $this->assign('list_language_translate', get_language_translate('author_center', 'title', $id));
        $this->assign("classify", $this->getClassify());
        $this->assign('data', $data);
        return $this->fetch();
    }

    function editPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();


            $data['title']=trim($data['title']);
            $data['title_en']=trim($data['title_en']);
            $data['classifyid']=trim($data['classifyid']);
            $data['thumb']=trim($data['thumb']);
            $data['active_start_time']=strtotime(trim($data['active_start_time']));
            $data['active_end_time']=strtotime(trim($data['active_end_time']));
            $data['submission_start_time']=strtotime(trim($data['submission_start_time']));
            $data['submission_end_time']=strtotime(trim($data['submission_end_time']));
            $data['activity_play']=trim($data['activity_play']);
            $data['related_topics']=implode(', ', $data['related_topics']);
            $data['activity_reward']=trim($data['activity_reward']);
            $title=$data['title'];
            $title_en=$data['title_en'];

            $id=$data['id'];

            if($title==""){
                $this->error("标题不能为空");
            }

            if($title_en==""){
                $this->error("Activity Title不能为空");
            }

            $isexit=DB::name('author_center')->where([['id','<>',$id],['title','=',$title]])->find();
            if($isexit){
                $this->error('该创作者分类名称已存在');
            }
            // 修改多国语言
            $language = $data['language'];
            unset($data['language']);
            $rs = DB::name('author_center')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }
            update_language_translate('author_center', 'title', $id, $language);

            $action="修改创作者活动：{$id}";
            setAdminLog($action);

            $this->success("修改成功！");
        }
    }
    
}
