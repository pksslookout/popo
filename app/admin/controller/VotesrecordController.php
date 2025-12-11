<?php

/**
 * 充值记录
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class VotesrecordController extends AdminbaseController {
    protected function getAction($k=''){
//        收支行为,1收礼物2弹幕3分销收益4家族长收益6房间收费7计时收费10守护11每观看60秒视频奖励
        $action=array(
//            '1'=>'邀请用户 ',
//            '2'=>'每天观看规定时长视频奖励',
//            '3'=>'收费视频收入',
//            '4'=>'视频送礼物',
//            '5'=>'直播间送礼物',
//            '6'=>'开通守护',
//            '7'=>'每观看60秒视频奖励',
            '1'=>'收礼物 ',
            '2'=>'弹幕',
            '3'=>'分销收益',
            '4'=>'家族长收益',
            '6'=>'房间收费',
            '7'=>'计时收费',
            '10'=>'守护',
            '11'=>'每观看60秒视频奖励',
            '12'=>'lala兑换钻石',
            '13'=>'lala兑换usdt',
            '14'=>'打赏-分享收益',
            '101'=>'利润',
            '102'=>'滑落',
        );
        if($k===''){
            return $action;
        }
        
        return isset($action[$k]) ? $action[$k]: '';
    }

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
        
        $action=isset($data['action']) ? $data['action']: '';
        if($action!=''){
            $map[]=['action','=',$action];
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

        $tfromid=isset($data['fromid']) ? $data['fromid']: '';
        if($tfromid!=''){
            $liangtouid=getLianguser($tfromid);
            if($liangtouid){
                $map[]=['fromid',['=',$tfromid],['in',$liangtouid],'or'];
            }else{
                $map[]=['fromid','=',$tfromid];
            }
        }

        $lists = Db::name("user_voterecord")
            ->where($map)
			->order("id desc")
			->paginate(20);
        
        $lists->each(function($v,$k){
			$v['userinfo']=getUserInfo($v['uid']);
			$v['touserinfo']=getUserInfo($v['fromid']);
            return $v;
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
        $this->assign('action', $this->getAction());

        $configpub=getConfigPub();

        $this->assign('name_votes',$configpub['name_votes']);
        
    	return $this->fetch();
    }

    function export()
    {

        $configpub=getConfigPub();
        $this->assign('name_coin',$configpub['name_votes']);
    
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

        $action=isset($data['action']) ? $data['action']: '';
        if($action!=''){
            $map[]=['action','=',$action];
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

        $fromid=isset($data['fromid']) ? $data['fromid']: '';
        if($fromid!=''){
            $liangtouid=getLianguser($fromid);
            if($liangtouid){
                $map[]=['fromid',['=',$fromid],['in',$liangtouid],'or'];
            }else{
                $map[]=['fromid','=',$fromid];
            }
        }
        
        
        $xlsName  = $configpub['name_votes']."收入记录";

        $xlsData=Db::name("user_voterecord")
            ->where($map)
            ->order('id desc')
			->select()
            ->toArray();
        foreach ($xlsData as $k => $v)
        {
            $userinfo=getUserInfo($v['uid']);
            $touserinfo=getUserInfo($v['fromid']);
            $xlsData[$k]['user_nicename']= $userinfo['user_nicename']."(".$v['uid'].")";
            $xlsData[$k]['to_user_nicename']= $touserinfo['user_nicename']."(".$v['fromid'].")";
            $xlsData[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
            $xlsData[$k]['action']=$this->getAction($v['action']);
        }

        $action="导出".$configpub['name_votes']."收入记录：".Db::name("user_voterecord")->getLastSql();
        setAdminLog($action);
        
        $cellName = array('A','B','C','D','E','F','G','H','I');
        $xlsCell  = array(
            array('id','序号'),
            array('action','收支行为'),
            array('user_nicename','收入用户(ID)'),
            array('to_user_nicename','行为用户(ID)'),
            array('votes',$configpub['name_votes']),
            array('addtime','时间')
        );
        exportExcel($xlsName,$xlsCell,$xlsData,$cellName);
    }

}
