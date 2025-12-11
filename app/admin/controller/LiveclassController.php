<?php

/**
 * 直播分类
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class LiveclassController extends AdminbaseController {
    function index(){
			
    	$lists = Db::name("live_class")
            //->where()
            ->order("list_order asc, id desc")
            ->paginate(20);
            
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
    	
    	return $this->fetch();
    }
		
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('live_class')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        delete_language_translate('live_class', 'name', $id);
        $action="删除直播分类：{$id}";
        setAdminLog($action);
                    
        $this->resetcache();
        $this->success("删除成功！");				
    }		
    //排序
    public function listOrder() { 
		
        $model = DB::name('live_class');
        parent::listOrders($model);
        
        $action="更新直播分类排序";
        setAdminLog($action);
        
        $this->resetcache();
        $this->success("排序更新成功！");
    }	
    

    function add(){
        $this->assign('list_language', get_language_all());
        return $this->fetch();
    }	
    function addPost(){
        if ($this->request->isPost()) {
            
            $data = $this->request->param();
            
			$name=$data['name'];

			if($name==""){
				$this->error("请填写名称");
			}
			$thumb=$data['thumb'];
			if($thumb==""){
				$this->error("请上传图标");
			}

            $des=$data['des'];
            if($des==''){
                $this->error("请填写直播分类描述");
            }

            if(mb_strlen($des)>200){
                $this->error("直播分类描述在200字以内");
            }
            // 新增多国语言
            $language = $data['language'];
            unset($data['language']);
            
			$id = DB::name('live_class')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('live_class', 'name', $id, $language);
            $action="添加直播分类：{$id}";
            setAdminLog($action);
            
            $this->resetcache();
            $this->success("添加成功！");
            
		}
    }		
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('live_class')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }
        $this->assign('list_language_translate', get_language_translate('live_class', 'name', $id));
        $this->assign('data', $data);
        return $this->fetch(); 			
    }
    
    function editPost(){
        if ($this->request->isPost()) {
            
            $data      = $this->request->param();
            
			$name=$data['name'];

			if($name==""){
				$this->error("请填写名称");
			}
			$thumb=$data['thumb'];
			if($thumb==""){
				$this->error("请上传图标");
			}

			$des=$data['des'];
            if($des==''){
                $this->error("请填写直播分类描述");
            }

            if(mb_strlen($des)>200){
                $this->error("直播分类描述在200字以内");
            }
            // 修改多国语言
            $language = $data['language'];
            unset($data['language']);
			$id = DB::name('live_class')->update($data);
            if($id===false){
                $this->error("修改失败！");
            }
            update_language_translate('live_class', 'name', $data['id'], $language);
            $action="修改直播分类：{$data['id']}";
            setAdminLog($action);
            
            $this->resetcache();
            $this->success("修改成功！");
		}	
    }
    
    function resetCache(){
        $key='getLiveClass';
        $rules= DB::name('live_class')
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
