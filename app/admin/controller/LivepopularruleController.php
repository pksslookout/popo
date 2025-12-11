<?php

/**
 * 上热门规则
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class LivepopularruleController extends AdminbaseController {
    function index(){
			
    	$lists = Db::name("live_popular_rule")
            //->where()
            ->order("list_order asc, id desc")
            ->paginate(20);
            
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

        $configPub=getConfigPub();
        $this->assign('configPub', $configPub);

    	return $this->fetch();
    }
		
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('live_popular_rule')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        
        $action="删除上热门规则：{$id}";
        setAdminLog($action);
                    
        $this->success("删除成功！");
    }		
    //排序
    public function listOrder() { 
		
        $model = DB::name('live_popular_rule');
        parent::listOrders($model);
        
        $action="更新上热门规则排序";
        setAdminLog($action);
        
        $this->success("排序更新成功！");
    }	
    

    function add(){
        $configPub=getConfigPub();
        $this->assign('configPub', $configPub);
        return $this->fetch();
    }	
    function addPost(){
        if ($this->request->isPost()) {
            
            $data = $this->request->param();
            
			$coin=$data['coin'];

			if($coin==""){
				$this->error("请填写云币");
			}
            
			$id = DB::name('live_popular_rule')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            
            $action="添加新上热门规则：{$id}";
            setAdminLog($action);
            
            $this->success("添加成功！");
            
		}
    }		
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('live_popular_rule')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }

        $configPub=getConfigPub();
        $this->assign('configPub', $configPub);
        $this->assign('data', $data);
        return $this->fetch();
    }
    
    function editPost(){
        if ($this->request->isPost()) {
            
            $data      = $this->request->param();

            $coin=$data['coin'];

            $configPub=getConfigPub();

            if($coin==""){
                $this->error("请填写".$configPub['name_coin']);
            }
			$id = DB::name('live_popular_rule')->update($data);
            if($id===false){
                $this->error("修改失败！");
            }
            
            $action="修改上热门规则：{$data['id']}";
            setAdminLog($action);
            
            $this->success("修改成功！");
		}	
    }
}
