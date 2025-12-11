<?php

/**
 * 文章分类管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdmintermController extends AdminbaseController {
    function index(){
        $data = $this->request->param();
    	$lists = Db::name("admin_term")
                ->order("id ASC")
                ->paginate(20);

        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

    	return $this->fetch();
    }

    function edit(){
        $id   = $this->request->param('id', 0, 'intval');

        $data=Db::name('admin_term')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }

        $this->assign('data', $data);
        return $this->fetch();
    }

    function editPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();


            $data['name']=trim($data['name']);
            $data['name_en']=trim($data['name_en']);
            $name=$data['name'];
            $name_en=$data['name_en'];

            $id=$data['id'];

            if($name==""){
                $this->error("分类名称不能为空");
            }

            if($name_en==""){
                $this->error("Category Name不能为空");
            }

            $isexit=DB::name('admin_term')->where([['id','<>',$id],['name','=',$name]])->find();
            if($isexit){
                $this->error('该分类名称已存在');
            }

            $rs = DB::name('admin_term')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }

            $action="修改内容分类管理：{$id}";
            setAdminLog($action);

            $this->success("修改成功！");
        }
    }

}
