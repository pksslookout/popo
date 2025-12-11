<?php

/**
 * 广告管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use cmf\lib\Upload;

class LanguageController extends AdminbaseController {

    function index(){
        $data = $this->request->param();

    	$lists = Db::name("language")->order('list_order asc')->paginate(20);

        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

    	return $this->fetch();
    }

    /* 上下架 */
    public function setStatus(){
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');
        if($id){

            $result=DB::name("language")->where(['id'=>$id])->update(['status'=>$status]);
            if($result){

                $action="修改语言({$id}),状态({$status})";
                setAdminLog($action);

                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->error('数据传入失败！');
        }

    }

    //排序
    public function listOrder() {

        $model = DB::name('language');
        parent::listOrders($model);


        $action="更新语言排序";
        setAdminLog($action);

        $this->success("排序更新成功！");

    }
    
}
