<?php

/**
 * 短视频-举报分类
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class VideorepcatController extends AdminbaseController {
    
	
	function index(){
        $lists = Db::name("video_report_classify")
			->order("list_order asc")
			->paginate(20);
        
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
    	
    	return $this->fetch();
	}
    
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('video_report_classify')->where("id={$id}")->delete();
        if($rs===false){
            $this->error("删除失败！");
        }
        delete_language_translate('video_report_classify', 'name', $id);
		$action="视频管理-删除举报分类ID: ".$id;
		setAdminLog($action);
        
        $this->success("删除成功！");
            
	}
    
    //排序
    public function listOrder() { 
		
        $model = DB::name('video_report_classify');
        parent::listOrders($model);
        
		
		$action="视频管理-更新举报分类排序";
		setAdminLog($action);
		
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

			if($name==""){
				$this->error("请填写名称");
			}
            
            $isexit=DB::name("video_report_classify")->where(['name'=>$name])->find();	
			if($isexit){
				$this->error('该名称已存在');
			}
			
            $data['addtime']=time();
            // 新增多国语言
            $language = $data['language'];
            unset($data['language']);
            
			$id = DB::name('video_report_classify')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('video_report_classify', 'name', $id, $language);
			$action="视频管理-添加举报分类ID: ".$id;
			setAdminLog($action);
            
            $this->success("添加成功！");
            
		}
	}
	
	
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('video_report_classify')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }
        $this->assign('list_language_translate', get_language_translate('video_report_classify', 'name', $id));
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
            $isexit=Db::name("video_report_classify")->where($where)->find();	
			if($isexit){
				$this->error('该名称已存在');
			}
            // 修改多国语言
            $language = $data['language'];
            unset($data['language']);
            
			$rs = DB::name('video_report_classify')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }
            update_language_translate('video_report_classify', 'name', $id, $language);
			
			$action="视频管理-编辑举报分类ID: ".$id;
			setAdminLog($action);
            
            $this->success("修改成功！");
		}
	}
    
}
