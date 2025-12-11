<?php

/**
 * 统计管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class StatisticsController extends AdminbaseController {
    protected function getStatisticsType($k=''){
        $action=array(
            'accumulative_popo'=>'累计收入',
            'accumulative_popo_output'=>'累计产出',
            'accumulative_popo_transfer'=>'累计划转',
            'accumulative_popo_contribution'=>'累计收益',
        );
        if($k===''){
            return $action;
        }

        return isset($action[$k]) ? $action[$k]: '';
    }
    function index(){
        $data = $this->request->param();
        $map=[];

        $lists = Db::name("statistics")
            ->where($map)
			->order("id asc")
			->paginate(20);

        $lists->appends($data);

        $lists->each(function($v,$k){
            $v['value']=$v['value']+$v['updatevalue'];
            return $v;
        });
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

        $this->assign('type', $this->getStatisticsType());
        
    	return $this->fetch();
    }
		
	function add(){
        $data = $this->request->param();
        $this->assign("data", $data);
		return $this->fetch();
	}

	function addPost(){
		if ($this->request->isPost()) {
            
            $data = $this->request->param();

			$id=$data['id'];
			$value=$data['value'];
			$type=$data['type'];
			if($value==""){
				$this->error("请填写数值");
			}

            if(!is_numeric($value)){
                $this->error("数值必须为数字");
            }

            $statisticsinfo=Db::name("statistics")->where(["id"=>$id])->find();

            if($type == 1){
                $value_up = $statisticsinfo['updatevalue']+$value;
            }else{
                $value_up = $statisticsinfo['updatevalue']-$value;
            }

            $currency=$statisticsinfo['currency'];
            $statistics_type=$statisticsinfo['statistics_type'];
            $data = [];
            $data['currency']=$currency;
            $data['statistics_type']=$statistics_type;
            $data['type']=$type;
            $data['action']=4;
            $data['nums']=$value;
            $data['total']=$value;
            $data['addtime']=time();
            Db::name("statistics")->where(["id"=>$id])->update(['updatevalue'=>$value_up]);
            
			$id = DB::name('statistics_record')->insertGetId($data);
            if(!$id){
                $this->error("修改失败！");
            }
            
			$action="修改统计余额ID：".$id;
			setAdminLog($action);

            $this->success("成功！",url("statistics/index"));
            
		}
	}
    

}
