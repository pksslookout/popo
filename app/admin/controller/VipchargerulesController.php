<?php

/**
 * 充值规则
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class VipchargerulesController extends AdminbaseController {

		
    function index(){
        
        $lists = Db::name("vip_charge_rules")
			->order("list_order asc")
			->paginate(20);
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

        $configpub=getConfigPub();
        $this->assign('name_coin',$configpub['name_coin']);
    	
    	return $this->fetch();
        
    }		
		
	function del(){
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('vip_charge_rules')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        delete_language_translate('vip_charge_rules', 'name', $id);
        $action="删除vip充值规则：{$id}";
        setAdminLog($action);
                    
        $this->resetcache();
        $this->success("删除成功！",url("Vipchargerules/index"));
	}
    
    //排序
    public function listOrder() { 
		
        $model = DB::name('vip_charge_rules');
        parent::listOrders($model);
        
        $action="更新vip充值规则排序";
        setAdminLog($action);
        
        $this->resetcache();
        $this->success("排序更新成功！");
        
    }	

	
    function add(){
        $configpub=getConfigPub();
        $this->assign('list_language', get_language_all());
        $this->assign('name_coin',$configpub['name_coin']);
		return $this->fetch();
    }	
	
    function addPost(){
		if ($this->request->isPost()) {
            
            $data = $this->request->param();

            $configpub=getConfigPub();

            $name=$data['name'];
            $money=$data['money'];
            $coin=$data['coin'];
            $days=$data['days'];

            if(!$name){
                $this->error("请填写名称");
            }

            if(!$money){
                $this->error("请填写价格");
            }

            if(!is_numeric($money)){
                $this->error("价格必须为数字");
            }

            if($money<=0||$money>99999999){
                $this->error("价格在0.01-99999999之间");
            }

            $data['money']=round($money,2);

            if(!$coin){
                $this->error("请填写".$configpub['name_coin']);
            }

            if(!is_numeric($coin)){
                $this->error($configpub['name_coin']."必须为数字");
            }

            if($coin<1||$coin>99999999){
                $this->error($configpub['name_coin']."在1-99999999之间");
            }

            if(floor($coin)!=$coin){
                $this->error($configpub['name_coin']."必须为整数");
            }

            if($days==''){
               $this->error("充值天数不能为空");
            }

            if(!is_numeric($days)){
                $this->error("充值天数必须为数字");
            }

            if($days<0||$days>99999999){
                $this->error("充值天数在0-99999999之间");
            }

            if(floor($days)!=$days){
                $this->error("充值天数必须为整数");
            }
            
            $data['addtime']=time();
            // 新增多国语言
            $language = $data['language'];
            unset($data['language']);
            
			$id = DB::name('vip_charge_rules')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('vip_charge_rules', 'name', $id, $language);
            $action="添加vip充值规则：{$id}";
            setAdminLog($action);
            
            $this->resetcache();
            $this->success("添加成功！");
            
		}
	}
    
    function edit(){
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('vip_charge_rules')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }

        $configpub=getConfigPub();
        $this->assign('name_coin',$configpub['name_coin']);
        $this->assign('list_language_translate', get_language_translate('vip_charge_rules', 'name', $id));
        $this->assign('data', $data);
        return $this->fetch();
        
    }		
	
    function editPost(){
		if ($this->request->isPost()) {
            
            $data = $this->request->param();

            $configpub=getConfigPub();

            $name=$data['name'];
            $money=$data['money'];
            $coin=$data['coin'];
            $days=$data['days'];

            if(!$name){
                $this->error("请填写名称");
            }

            if(!$money){
                $this->error("请填写价格");
            }

            if(!is_numeric($money)){
                $this->error("价格必须为数字");
            }

            if($money<=0||$money>99999999){
                $this->error("价格在0.01-99999999之间");
            }

            $data['money']=round($money,2);

            if(!$coin){
                $this->error("请填写".$configpub['name_coin']);
            }

            if(!is_numeric($coin)){
                $this->error($configpub['name_coin']."必须为数字");
            }

            if($coin<1||$coin>99999999){
                $this->error($configpub['name_coin']."在1-99999999之间");
            }

            if(floor($coin)!=$coin){
                $this->error($configpub['name_coin']."必须为整数");
            }

            if($days==''){
                $this->error("充值天数不能为空");
            }

            if(!is_numeric($days)){
                $this->error("充值天数必须为数字");
            }

            if($days<0||$days>99999999){
                $this->error("充值天数在0-99999999之间");
            }

            if(floor($days)!=$days){
                $this->error("充值天数必须为整数");
            }
            // 修改多国语言
            $language = $data['language'];
            unset($data['language']);
            
			$rs = DB::name('vip_charge_rules')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }
            update_language_translate('vip_charge_rules', 'name', $data['id'], $language);
            $action="修改vip充值规则：{$data['id']}";
            setAdminLog($action);
            
            $this->resetcache();
            $this->success("修改成功！");
		}
	}
    	

    function resetcache(){
        $key='getVipChargeRules';
        $rules= DB::name("vip_charge_rules")
            ->order('list_order asc')
            ->select();
        if($rules){
            setcaches($key,$rules);
        }else{
			delcache($key);
		}
        return 1;
    }
}
