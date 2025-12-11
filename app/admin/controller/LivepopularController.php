<?php

/**
 * 广告管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use cmf\lib\Upload;

class LivepopularController extends AdminbaseController {

    function index(){
        $data = $this->request->param();
        $map=[];

        $status=isset($data['status']) ? $data['status']: '';
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';

        if($status!=""){
            $map[]=['status','=',$status];
        }

        if($start_time!=""){
            $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
            $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }

        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['uid|id','=',$keyword];
        }
			
    	$lists = Db::name("live_popular")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);

        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

        $configPub = getConfigPub();
    	$this->assign("configPub", $configPub);


    	return $this->fetch();
    }
    
}
