<?php

/**
 * 视频分类
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class VideoclassController extends AdminbaseController {
    function index(){
        $lists = Db::name("video_class")
			->order("list_order asc")
			->paginate(20);
        
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
    	
    	return $this->fetch();
        
    }
		
    function del(){
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('video_class')->where("id={$id}")->delete();
        if($rs===false){
            $this->error("删除失败！");
        }
        delete_language_translate('video_class', 'name', $id);
        
        $action="删除视频分类：{$id}";
        setAdminLog($action);
        $this->resetCache();
                
        $this->success("删除成功！");				
    }		
    //排序
    public function listOrder() { 
		
        $model = DB::name('video_class');
        parent::listOrders($model);
        
        $action="更新视频分类排序";
        setAdminLog($action);
        $this->resetCache();
            
        $this->success("排序更新成功！");
    }	
    

    function add(){
        $this->assign('list_language', get_language_all());
        return $this->fetch();
    }
    function addPost(){
		if ($this->request->isPost()) {
            
            $data      = $this->request->param();
            
			$name=$data['name'];
			$name_en=$data['name_en'];

			if($name==""){
				$this->error("请填写名称");
			}

			if($name_en==""){
				$this->error("Video Category Name");
			}
            
            $isexit=DB::name("video_class")->where(['name'=>$name])->find();	
			if($isexit){
				$this->error('该名称已存在');
			}
            // dump($data);die;
            $data['addtime'] = time();
            // 新增多国语言
            $language = $data['language'];
            unset($data['language']);
			$id = DB::name('video_class')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('video_class', 'name', $id, $language);
            $action="添加视频分类：{$id}";
            setAdminLog($action);
            $this->resetCache();
            
            $this->success("添加成功！");
            
		}
	}	
    
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('video_class')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }
        $this->assign('list_language_translate', get_language_translate('video_class', 'name', $id));
        $this->assign('data', $data);
        return $this->fetch();			
    }
    
    function editPost(){
		if ($this->request->isPost()) {
            
            $data      = $this->request->param();
            
			$name=$data['name'];
			$id=$data['id'];

			if($name==""){
				$this->error("请填写名称");
			}
            $where=[];
            $where[]=['id','<>',$id];
            $where[]=['name','=',$name];
            $isexit=Db::name("video_class")->where($where)->find();	
			if($isexit){
				$this->error('该名称已存在');
			}

            $data['addtime'] = time();
            // 修改多国语言
            $language = $data['language'];
            unset($data['language']);
			$rs = DB::name('video_class')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }
            update_language_translate('author_center_classify', 'name', $id, $language);
            $action="修改视频分类：{$id}";
            setAdminLog($action);
            $this->resetCache();
                
            $this->success("修改成功！");
		}
	}
    
    function resetCache(){
        $key='getVideoClass';
        $rules= Db::name("video_class")
            ->where(['is_status'=>1])
            ->order('list_order asc,id desc')
            ->select();
        if($rules){
            setcaches($key,$rules);
        }else{
			delcache($key);
		}
        
        return 1;
    }
}
