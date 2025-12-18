<?php
/**
 * 定时任务
 */
namespace app\appapi\controller;

use think\Controller;
use think\Db;

class CrontabController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }

    function resetScore(){
        Db::name("user")->where('today_score', '>', 0)->update(['today_score'=>0]);
    }

    function resetTeamUpdateStatusDealTime(){
        Db::name("user")->where('team_update_status_deal_time', '>', 0)->update(['team_update_status_deal_time'=>0]);
    }

    function checkUpgradeUser(){
        $time = 3600;
        $key = 'checkUpgradeUser';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $list_user=Db::name("user")->field('id,team_level')->where(["team_update_status"=>1,'team_update_status_deal_time'=>0])->limit(0,20)->order('team_level asc')->select();
            if(!$list_user){
                setcaches($key,1,$time);
                exit();
            }
        }else{
            exit();
        }
        $id_arr = [];
        foreach($list_user as $k=>$v){
            $id_arr[] = $v['id'];
            if($v['team_level']==0){
                Db::name("user")->where('id', $v['id'])->update(['team_level'=>1]);
            }
            if($v['team_level']==1){
                $this->dealUpgradeUser($v['id'],2,2);
            }
            if($v['team_level']==2){
                $this->dealUpgradeUser($v['id'],2,3);
            }
            if($v['team_level']==3){
                $this->dealUpgradeUser($v['id'],2,4);
            }
            if($v['team_level']==4){
                $this->dealUpgradeUser($v['id'],2,5);
            }
        }
        if($id_arr){
            Db::name("user")->where('id', 'in', $id_arr)->update(['team_update_status_deal_time'=>1]);
        }
        sleep(1);
        $configpub=getConfigPub();
        header('Location: ' . $configpub['site'] . $_SERVER['REQUEST_URI']);
    }

    function checkUpgradeUserTwo(){
        $time = 3600;
        $key = 'checkUpgradeUserTwo';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $list_user=Db::name("user")->field('id,team_level')->where(["team_update_status"=>1,'team_update_status_deal_time'=>1])->limit(0,20)->order('team_level asc')->select();
            if(!$list_user){
                setcaches($key,1,$time);
                exit();
            }
        }else{
            exit();
        }
        $id_arr = [];
        foreach($list_user as $k=>$v){
            $id_arr[] = $v['id'];
            if($v['team_level']==0){
                Db::name("user")->where('id', $v['id'])->update(['team_level'=>1]);
            }
            if($v['team_level']==1){
                $this->dealUpgradeUser($v['id'],2,2);
            }
            if($v['team_level']==2){
                $this->dealUpgradeUser($v['id'],2,3);
            }
            if($v['team_level']==3){
                $this->dealUpgradeUser($v['id'],2,4);
            }
            if($v['team_level']==4){
                $this->dealUpgradeUser($v['id'],2,5);
            }
        }
        if($id_arr){
            Db::name("user")->where('id', 'in', $id_arr)->update(['team_update_status_deal_time'=>2]);
        }
        sleep(1);
        $configpub=getConfigPub();
        header('Location: ' . $configpub['site'] . $_SERVER['REQUEST_URI']);
    }

    function dealUpgradeUser($one_uid, $up_count, $team_level){
        $up = 0;
        $up_level = $team_level-1;
        // 检测直推
        $list_agent=Db::name("agent")
            ->alias('a')
            ->leftJoin('user u','u.id=a.uid')
            ->field('a.uid,u.team_level')
            ->where(["one_uid"=>$one_uid])
            ->order("u.team_level DESC")
            ->select();
        foreach($list_agent as $v_agent){
            if($v_agent['team_level']>=$up_level){
                $up = $up+1;
                if($up>=$up_count){
                    break;
                }
            }
        }
        // 检测直推团队
        if($up<$up_count){
            foreach($list_agent as $v_agent){
                $ids = userIdList($v_agent['uid']);
                $list_agent_team=Db::name("user")
                    ->field('team_level')
                    ->where('id', 'in', $ids)
                    ->order("team_level DESC")
                    ->select();
                foreach($list_agent_team as $v_agent_team){
                    if($v_agent_team['team_level']>=$up_level){
                        $up = $up+1;
                        if($up>=$up_count){
                            break;
                        }
                    }
                }
            }
        }
        if($up>=$up_count){
            Db::name("user")->where('id',$one_uid)->update(['team_level'=>$team_level]);
        }
    }

    function statistics(){
        $sum_popo_pool = Db::name("user")->where('user_type', 2)->where('id', '>', 10)->where('popo_pool', '>', 0)->sum('popo_pool');
        Db::name("statistics")->where(["currency"=>'popo',"statistics_type"=>'accumulative_popo_output'])->update(['value'=>$sum_popo_pool]);
    }

    function rewardVideoPopular(){

        $time = 3600;
        $key = 'CrontabRewardVideoPopular';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $deal_arr = [
                'deal',
                'deal1',
                'deal2',
                'deal3',
                'deal4',
                'deal5',
                'deal6',
                'deal7',
            ];
            setcaches($key,$deal_arr,$time);
        }

        // 市场分红处理
        $key_r = 'CrontabRewardVideoPopularResult';
        $result=getcaches($key_r);
        if(!$result){
            $result = Db::query('SELECT MIN(id) AS min_id, MAX(id) AS max_id, SUM(price) - SUM(return_price) AS total_coin FROM cmf_popular WHERE is_deal = 0;');
            if($result){
                setcaches($key_r,$result,$time);
            }
        }

        if(!empty($result[0]['min_id'])){
            $ratio=1;
            $min_id = $result[0]['min_id'];
            $max_id = $result[0]['max_id'];
            $total = $result[0]['total_coin'] * $ratio;
            $type = 'video_popular';
            if(in_array('deal',$deal_arr)){
                $this->deal($total,$min_id,$max_id,$deal_arr,$key);
            }
            if(in_array('deal1',$deal_arr)){
                $this->deal1($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal2',$deal_arr)){
                $this->deal2($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal3',$deal_arr)){
                $this->deal3($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal4',$deal_arr)){
                $this->deal4($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal5',$deal_arr)){
                $this->deal5($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal6',$deal_arr)){
                $this->deal6($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal7',$deal_arr)){
                $this->deal7($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
        }
    }

    function rewardLivePopular(){

        $time = 3600;
        $key = 'CrontabRewardLivePopular';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $deal_arr = [
                'deal',
                'deal1',
                'deal2',
                'deal3',
                'deal4',
                'deal5',
                'deal6',
                'deal7',
            ];
            setcaches($key,$deal_arr,$time);
        }

        // 市场分红处理
        $key_r = 'CrontabRewardLivePopularResult';
        $result=getcaches($key_r);
        if(!$result){
            $result = Db::query('SELECT MIN(id) AS min_id, MAX(id) AS max_id, SUM(price) - SUM(return_price) AS total_coin FROM cmf_live_popular WHERE is_deal = 0;');
            if($result){
                setcaches($key_r,$result,$time);
            }
        }

        if(!empty($result[0]['min_id'])){
            $ratio=1;
            $min_id = $result[0]['min_id'];
            $max_id = $result[0]['max_id'];
            $total = $result[0]['total_coin'] * $ratio;
            $type = 'live_popular';
            if(in_array('deal',$deal_arr)){
                $this->deal($total,$min_id,$max_id,$deal_arr,$key);
            }
            if(in_array('deal1',$deal_arr)){
                $this->deal1($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal2',$deal_arr)){
                $this->deal2($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal3',$deal_arr)){
                $this->deal3($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal4',$deal_arr)){
                $this->deal4($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal5',$deal_arr)){
                $this->deal5($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal6',$deal_arr)){
                $this->deal6($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
            if(in_array('deal7',$deal_arr)){
                $this->deal7($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type);
            }
        }
    }

    function rewardVideoCoin(){

        $time = 3600;
        $key = 'CrontabRewardVideoCoin';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $deal_arr = [
                'deal',
                'deal1',
                'deal2',
                'deal3',
                'deal4',
                'deal5',
                'deal6',
                'deal7',
            ];
            setcaches($key,$deal_arr,$time);
        }

        // 市场分红处理
        $key_r = 'CrontabRewardVideoCoinResult';
        $result=getcaches($key_r);
        if(!$result){
            $result = Db::query('SELECT MIN(id) AS min_id, MAX(id) AS max_id, SUM(coin) AS total_coin FROM cmf_video_coin WHERE status = 1;');
            if($result){
                setcaches($key_r,$result,$time);
            }
        }

        if(!empty($result[0]['min_id'])){
            $ratio=1;
            $min_id = $result[0]['min_id'];
            $max_id = $result[0]['max_id'];
            $total = $result[0]['total_coin'] * $ratio;
            if(in_array('deal',$deal_arr)){
                $this->deal($total,$min_id,$max_id,$deal_arr,$key);
            }
            if(in_array('deal1',$deal_arr)){
                $this->deal1($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
            if(in_array('deal2',$deal_arr)){
                $this->deal2($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
            if(in_array('deal3',$deal_arr)){
                $this->deal3($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
            if(in_array('deal4',$deal_arr)){
                $this->deal4($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
            if(in_array('deal5',$deal_arr)){
                $this->deal5($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
            if(in_array('deal6',$deal_arr)){
                $this->deal6($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
            if(in_array('deal7',$deal_arr)){
                $this->deal7($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video_coin');
            }
        }
    }

    function rewardVideoCoinGift(){
        // 市场分红处理
        $ratio=1; // 钻石：LALA
        $list_coin=Db::name("video_coin")->where(["status"=>0])->limit(0,40)->select();
        foreach ($list_coin as $key => $value) {
            $uid = $value['uid'];
            $total = $value['coin'];
            $ratio_total = $total*$ratio;
            $action = 6;
            $giftid = $value['id'];
            $giftcount = 1;
            $showid=$value['videoid'];
            $nowtime=time();
            $this->dealGift($uid,$total,$ratio_total,$action,$giftid,$giftcount,$showid,$nowtime);
        }

        Db::name("video_coin")->where(["status"=>0])->update(['status'=>1]);

    }

    function rewardVideo(){

        $time = 3600;
        $key = 'CrontabRewardVideo';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $deal_arr = [
                'deal',
                'deal1',
                'deal2',
                'deal3',
                'deal4',
                'deal5',
                'deal6',
                'deal7',
            ];
            setcaches($key,$deal_arr,$time);
        }

        // 市场分红处理
        $key_r = 'CrontabRewardVideoResult';
        $result=getcaches($key_r);
        if(!$result){
            $result = Db::query('SELECT MIN(id) AS min_id, MAX(id) AS max_id, SUM(coin) AS total_coin FROM cmf_video_gift WHERE status = 1;');
            if($result){
                setcaches($key_r,$result,$time);
            }
        }

        if(!empty($result[0]['min_id'])){
            $ratio=1;
            $min_id = $result[0]['min_id'];
            $max_id = $result[0]['max_id'];
            $total = $result[0]['total_coin'] * $ratio;
            if(in_array('deal',$deal_arr)){
                $this->deal($total,$min_id,$max_id,$deal_arr,$key);
            }
            if(in_array('deal1',$deal_arr)){
                $this->deal1($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
            if(in_array('deal2',$deal_arr)){
                $this->deal2($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
            if(in_array('deal3',$deal_arr)){
                $this->deal3($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
            if(in_array('deal4',$deal_arr)){
                $this->deal4($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
            if(in_array('deal5',$deal_arr)){
                $this->deal5($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
            if(in_array('deal6',$deal_arr)){
                $this->deal6($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
            if(in_array('deal7',$deal_arr)){
                $this->deal7($total,$min_id,$max_id,$deal_arr,$key,$key_r,'video');
            }
        }
    }

    function rewardVideoGift(){

//        Db::startTrans();
//
//        try {
        // 市场分红处理
        $ratio=1; // 钻石：LALA
        $list_gift=Db::name("video_gift")->where(["status"=>0])->limit(0,40)->select();
        $id_arr=[];
        foreach ($list_gift as $value) {
            $id_arr[] = $value['id'];
            $uid = $value['uid'];
            $total = $value['coin'];
            $ratio_total = $total*$ratio;
            $action = 1;
            $giftid = $value['giftid'];
            $giftcount = $value['number'];
            $showid=$value['videoid'];
            $nowtime=time();
            $this->dealGift($uid,$total,$ratio_total,$action,$giftid,$giftcount,$showid,$nowtime);
        }

        if(!empty($id_arr)){
            Db::name("video_gift")->where('id', 'in', $id_arr)->update(['status'=>1]);
        }

//            Db::commit();
//
//        } catch (\Exception $e) {
//
//            Db::rollback();
//            echo '操作失败！';
//
//        }

    }

    function rewardLive(){
        // 市场分红处理
        $time = 3600;
        $key = 'CrontabRewardLive';
        $deal_arr=getcaches($key);
        if(!$deal_arr){
            $deal_arr = [
                'deal',
                'deal1',
                'deal2',
                'deal3',
                'deal4',
                'deal5',
                'deal6',
                'deal7',
            ];
            setcaches($key,$deal_arr,$time);
        }

        // 市场分红处理
        $key_r = 'CrontabRewardLiveResult';
        $result=getcaches($key_r);
        if(!$result){
            $result = Db::query('SELECT MIN(id) AS min_id, MAX(id) AS max_id, SUM(coin) AS total_coin FROM cmf_live_gift WHERE status = 1;');
            if($result){
                setcaches($key_r,$result,$time);
            }
        }

        if(!empty($result[0]['min_id'])){
            $ratio=1;
            $min_id = $result[0]['min_id'];
            $max_id = $result[0]['max_id'];
            $total = $result[0]['total_coin'] * $ratio;
            if(in_array('deal',$deal_arr)){
                $this->deal($total,$min_id,$max_id,$deal_arr,$key);
            }
            if(in_array('deal1',$deal_arr)){
                $this->deal1($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
            if(in_array('deal2',$deal_arr)){
                $this->deal2($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
            if(in_array('deal3',$deal_arr)){
                $this->deal3($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
            if(in_array('deal4',$deal_arr)){
                $this->deal4($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
            if(in_array('deal5',$deal_arr)){
                $this->deal5($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
            if(in_array('deal6',$deal_arr)){
                $this->deal6($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
            if(in_array('deal7',$deal_arr)){
                $this->deal7($total,$min_id,$max_id,$deal_arr,$key,$key_r,'live');
            }
        }
    }

    function rewardLiveGift(){

//        Db::startTrans();
//
//        try {
        // 市场分红处理
        $ratio=1; // 钻石：LALA
        $list_gift=Db::name("live_gift")->where(["status"=>0])->limit(0,40)->select();
        $id_arr=[];
        foreach ($list_gift as $key => $value) {
            $id_arr[] = $value['id'];
            $uid = $value['uid'];
            $total = $value['coin'];
            $ratio_total = $total*$ratio;
            $action = 1;
            $giftid = $value['giftid'];
            $giftcount = $value['number'];
            $showid=$value['showid'];
            $nowtime=time();
            $this->dealGift($uid,$total,$ratio_total,$action,$giftid,$giftcount,$showid,$nowtime);
        }

        if(!empty($id_arr)){
            Db::name("live_gift")->where('id', 'in', $id_arr)->update(['status'=>1]);
        }

//            Db::commit();
//
//        } catch (\Exception $e) {
//
//            Db::rollback();
//            echo '操作失败！';
//
//        }
    }

    function transferPoPoDividend(){

        $time = 3600;
        $p_count = 1;

        // 市场分红处理
        $key = 'CrontabTransferPoPoDividend';
        $count_arr=getcaches($key);
        if(!$count_arr){
            $count_popo_pool = Db::name("user")->where('user_type', 2)->where('id', '>', 10)->where('popo_pool', '>', 0)->count();
            $count_arr = divideNumberIntoArray($count_popo_pool, $p_count);
            if($count_arr){
                setcaches($key,$count_arr,$time);
            }else{
                exit();
            }
        }

        $user_count = $count_arr[0];

        $nowtime = time();
        $key_r = 'CrontabTransferPoPoDividendResult';
        $result=getcaches($key_r);
        if(!$result){
            $result = Db::query('SELECT MIN(id) AS min_id, MAX(id) AS max_id, SUM(nums) AS total_popo FROM cmf_user_poporecord WHERE action = 5 and status = 0;');
            if($result){
                setcaches($key_r,$result,$time);
            }
        }
        if(!empty($result[0]['min_id'])){
            $ratio=1;
            $min_id = $result[0]['min_id'];
            $max_id = $result[0]['max_id'];
            $total = $result[0]['total_popo'] * $ratio;
            $total = $total * 0.14;

            $key_s = 'CrontabTransferPoPoDividendSum';
            $sum_popo_pool=getcaches($key_s);
            if(!$sum_popo_pool){
                $sum_popo_pool = Db::name("user")->where('user_type', 2)->where('id', '>', 10)->where('popo_pool', '>', 0)->sum('popo_pool');
                if($sum_popo_pool){
                    setcaches($key_s,$sum_popo_pool,$time);
                }else{
                    exit();
                }
            }

            $key_P = 'CrontabTransferPoPoDividendListUserP';
            $p=getcaches($key_P);
            if(!$p){
                $p = 1;
                setcaches($key_P,$p,$time);
            }

            $jump = $p_count*($p-1);
            $list_user=Db::name("user")->where(["user_type"=>2])->where('id', '>', 10)->where('popo_pool', '>', 0)->field('id,popo_pool')->order('id asc')->limit($jump,$user_count)->select();

            setcaches($key_P,$p+1,$time);

            foreach($list_user as $k=>$v){
                $nums = $total*($v['popo_pool']/$sum_popo_pool);
                $insert_popo=[
                    'type'=>'1',
                    'action'=>4,
                    'uid'=>$v['id'],
                    'actionid'=>$min_id,
                    'lastactionid'=>$max_id,
                    'nums'=>$nums,
                    'total'=>$nums,
                    'addtime'=>$nowtime,
                ];
                Db::name("user_popopoolrecord")->insert($insert_popo);
                Db::name("user")->where(['id'=>$v['id']])->inc('popo_pool',$nums)->inc('popo_accumulative', $nums)->update();
            }

            if(count($count_arr)>1){
                unset($count_arr[0]);
                $count_arr = array_values($count_arr);
                setcaches($key,$count_arr,3600);
                $configpub=getConfigPub();
                sleep(1);
                header('Location: ' . $configpub['site'].$_SERVER['REQUEST_URI']);
                exit;
            }else{
                Db::name("user_poporecord")->where(["action"=>5,"status"=>0])->where('id', '<=', $max_id)->update(['status'=>1]);
                delcache($key);
                delcache($key_P);
                exit;
            }
        }else{
            delcache($key);
            exit;
        }

    }

    protected function dealGift($uid,$total,$ratio_total,$action,$giftid,$giftcount,$showid,$nowtime){

        $family_ratio = array(
            1=>0.06,
            2=>0.12,
            3=>0.18,
        );
        // 打赏公会 收益
        // 查询是否公会会长
        $adminfamilyid = 1;
        $family_user_count = 0;
        $family =Db::name("family")->field('id,votes')->where('uid', $uid)->find();
        if(!$family){
            $family =Db::name("family_user")->field('familyid')->where('uid', $uid)->find();
            if($family){
                $family =Db::name("family")->field('id,votes')->where('id', $family['familyid'])->find();
                $family_user_count = Db::name("family_user")->where('familyid', $family['familyid'])->count();
            }
        }
        if($family&&$family_user_count>3) {
            $level_family = getLevelFamily($family['votestotal']);
            $level_family_ratio= $family_ratio;
            $ratio_family = $level_family_ratio[$level_family];
            $family_total = $ratio_total*$ratio_family;
            $insert_votes=[
                'type'=>'1',
                'action'=>$action,
                'familyid'=>$family['id'],
                'fromid'=>$uid,
                'actionid'=>$giftid,
                'nums'=>$giftcount,
                'total'=>$total,
                'showid'=>$showid,
                'votes'=>$family_total,
                'addtime'=>$nowtime,
            ];
            Db::name("family_voterecord")->insert($insert_votes);
            Db::name("family")->where(['id'=>$family['id']])->inc('votes',$family_total)->inc('votesearnings', $family_total)->update();
            // 接收滑落映票到管理员账号
            if($ratio_family<0.18){
                $ratio_family = 0.18-$level_family_ratio[$level_family];
                $family_total = $family_total*$ratio_family;
                $insert_votes=[
                    'type'=>'1',
                    'action'=>$action,
                    'familyid'=>$adminfamilyid,
                    'fromid'=>$uid,
                    'actionid'=>$giftid,
                    'nums'=>$giftcount,
                    'total'=>$total,
                    'showid'=>$showid,
                    'votes'=>$family_total,
                    'addtime'=>$nowtime,
                ];
                Db::name("family_voterecord")->insert($insert_votes);
                Db::name("family")->where(['id'=>$adminfamilyid])->inc('votes',$family_total)->inc('votesearnings', $family_total)->update();
            }
        }else{
            // 直接18%滑落
            $ratio_family = 0.18;
            $family_total = $ratio_total*$ratio_family;
            $insert_votes=[
                'type'=>'1',
                'action'=>$action,
                'familyid'=>$adminfamilyid,
                'fromid'=>$uid,
                'actionid'=>$giftid,
                'nums'=>$giftcount,
                'total'=>$total,
                'showid'=>$showid,
                'votes'=>$family_total,
                'addtime'=>$nowtime,
            ];
            Db::name("family_voterecord")->insert($insert_votes);
            Db::name("family")->where(['id'=>$adminfamilyid])->inc('votes',$family_total)->inc('votesearnings', $family_total)->update();
        }

        // 市场推广收益
        $agent =Db::name("agent")
            ->field('relation_chain')
            ->where('uid', $uid)
            ->find();
        if($agent){
            $relation_chain = explode(',',$agent['relation_chain']);
            $relation_chain_count = count($relation_chain);
            $ratio_promotion = 0.02;
            $promotion_total = $ratio_total*$ratio_promotion;
            for ($i=0;$i<3;$i++){
                $add = 1;
                if(isset($relation_chain[$relation_chain_count-$i-1])){
                    $promotion_uid = $relation_chain[$relation_chain_count-$i-1];
                    if($i>0){
                        $find_vip_user =Db::name("vip_user")
                            ->field('uid')
                            ->where('uid', $promotion_uid)
                            ->find();
                        if(!$find_vip_user){
                            $add = 0;
                        }
                    }
                }else{
                    $add = 0;
                }
                if($add==1){
                    $insert_votes=[
                        'type'=>'1',
                        'action'=>14,
                        'uid'=>$promotion_uid,
                        'fromid'=>$uid,
                        'actionid'=>$giftid,
                        'nums'=>$giftcount,
                        'total'=>$total,
                        'showid'=>$showid,
                        'votes'=>$promotion_total,
                        'addtime'=>$nowtime,
                    ];
                    Db::name("user_voterecord")->insert($insert_votes);
                    Db::name("user")->where(['id'=>$promotion_uid])->inc('votes',$promotion_total)->inc('votesearnings', $promotion_total)->update();
                }
            }
        }

        // 平台利润
        $platform_total=$ratio_total;
        $platform_uid = 2;
        $ratio_platform = 0.02;
        $platform_total = $platform_total*$ratio_platform;
        $insert_votes=[
            'type'=>'1',
            'action'=>101,
            'uid'=>$platform_uid,
            'fromid'=>$uid,
            'actionid'=>$giftid,
            'nums'=>$giftcount,
            'total'=>$total,
            'showid'=>$showid,
            'votes'=>$platform_total,
            'addtime'=>$nowtime,
        ];
        Db::name("user_voterecord_platform")->insert($insert_votes);
        Db::name("user")->where(['id'=>$platform_uid])->inc('votes',$platform_total)->inc('votesearnings', $platform_total)->update();

        // 累计POPO
        $platform_total=$ratio_total;
        $ratio_platform = 0.14;
        $platform_total = $platform_total*$ratio_platform;
        $currency = 'popo';
        $statistics_type = 'accumulative_popo';
        $insert_votes=[
            'type'=>'1',
            'action'=>$action,
            'uid'=>$platform_uid,
            'fromid'=>$uid,
            'actionid'=>$giftid,
            'nums'=>$giftcount,
            'total'=>$platform_total,
            'currency'=>$currency,
            'statistics_type'=>$statistics_type,
            'addtime'=>$nowtime,
        ];
        Db::name("statistics_record")->insert($insert_votes);
        Db::name("statistics")->where(['currency'=>$currency,'statistics_type'=>$statistics_type])->setInc('value',$platform_total);
    }

    protected function deal($total,$min_id,$max_id,$deal_arr,$key){
        $total_output = $total * 0.14;
        $nowtime = time();

//        $currency = 'popo';
//        $statistics_type = 'accumulative_popo_output';
//
//        $info_statistics = Db::name("statistics")->where(["currency"=>$currency,"statistics_type"=>$statistics_type])->find();
//
//        if($info_statistics['updatetime']<strtotime('today midnight')){
//            $insert_statistics=[
//                'type'=>'1',
//                'action'=>3,
//                'uid'=>0,
//                'fromid'=>0,
//                'actionid'=>$min_id,
//                'lastactionid'=>$max_id,
//                'nums'=>$total_output,
//                'total'=>$total_output,
//                'currency'=>$currency,
//                'statistics_type'=>$statistics_type,
//                'addtime'=>$nowtime,
//            ];
//            Db::name("statistics_record")->insert($insert_statistics);
//            Db::name("statistics")->where(["currency"=>$currency,"statistics_type"=>$statistics_type])->setInc('value', $total_output);
//            Db::name("statistics")->where(["currency"=>$currency,"statistics_type"=>$statistics_type])->update(['updatetime'=>$nowtime]);
//        }

        unset($deal_arr[0]);
        $deal_arr = array_values($deal_arr);
        setcaches($key,$deal_arr,3600);
        $configpub=getConfigPub();
        sleep(1);
        header('Location: ' . $configpub['site'].$_SERVER['REQUEST_URI']);
        exit;
    }

    protected function deal_end($key_r,$max_id,$type)
    {
        delcache($key_r);
        if($type=='live_popular'){
            Db::name("live_popular")->where(["is_deal"=>0])->where('id', '<=', $max_id)->update(['is_deal'=>1]);
        }
        if($type=='video_popular'){
            Db::name("popular")->where(["is_deal"=>0])->where('id', '<=', $max_id)->update(['is_deal'=>1]);
        }
        if($type=='video_coin'){
            Db::name("video_coin")->where(["status"=>1])->where('id', '<=', $max_id)->update(['status'=>2]);
        }
        if($type=='video'){
            Db::name("video_gift")->where(["status"=>1])->where('id', '<=', $max_id)->update(['status'=>2]);
        }
        if($type=='live'){
            Db::name("live_gift")->where(["status"=>1])->where('id', '<=', $max_id)->update(['status'=>2]);
        }
    }

    protected function deal1($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal1';
        $key_total_1 = 'CrontabRewardVideoDealTotal1';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>1,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];

        $key_P = 'CrontabRewardVideoDeal1P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>1,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->extracted($list_mine, $min_id, $max_id, $total_1, $key_P, $p, $time, $count_arr, $key_1, $deal_arr, $key, $key_total_1);

    }

    protected function deal2($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal2';
        $key_total_1 = 'CrontabRewardVideoDealTotal2';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>2,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];

        $key_P = 'CrontabRewardVideoDeal2P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>2,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->extracted($list_mine, $min_id, $max_id, $total_1, $key_P, $p, $time, $count_arr, $key_1, $deal_arr, $key, $key_total_1);

    }

    protected function deal3($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal3';
        $key_total_1 = 'CrontabRewardVideoDealTotal3';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>3,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];

        $key_P = 'CrontabRewardVideoDeal3P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>3,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->extracted($list_mine, $min_id, $max_id, $total_1, $key_P, $p, $time, $count_arr, $key_1, $deal_arr, $key, $key_total_1);

    }

    protected function deal4($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal4';
        $key_total_1 = 'CrontabRewardVideoDealTotal4';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>4,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];
        $nowtime = time();

        $key_P = 'CrontabRewardVideoDeal4P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>4,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->extracted($list_mine, $min_id, $max_id, $total_1, $key_P, $p, $time, $count_arr, $key_1, $deal_arr, $key, $key_total_1);

    }

    protected function deal5($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal5';
        $key_total_1 = 'CrontabRewardVideoDealTotal5';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>5,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];

        $key_P = 'CrontabRewardVideoDeal5P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>5,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->extracted($list_mine, $min_id, $max_id, $total_1, $key_P, $p, $time, $count_arr, $key_1, $deal_arr, $key, $key_total_1);

    }

    protected function deal6($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal6';
        $key_total_1 = 'CrontabRewardVideoDealTotal6';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>6,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];

        $key_P = 'CrontabRewardVideoDeal6P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>6,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->extracted($list_mine, $min_id, $max_id, $total_1, $key_P, $p, $time, $count_arr, $key_1, $deal_arr, $key, $key_total_1);

    }

    protected function deal7($total,$min_id,$max_id,$deal_arr,$key,$key_r,$type){

        $time = 3600;

        $key_1 = 'CrontabRewardVideoDeal7';
        $key_total_1 = 'CrontabRewardVideoDealTotal7';
        $count_arr=getcaches($key_1);
        $total_1=getcaches($key_total_1);

        $p_count = 500;

        if(!$count_arr){
            $list_count=Db::name("user_mine_machine")->where(["level"=>7,"status"=>1])->count();
            if($list_count==0){
                delcache($key);
                $this->deal_end($key_r,$max_id,$type);
                exit;
            }
            $total = $total * 0.02;
            $total_1 = $total/$list_count;
            $count_arr = divideNumberIntoArray($list_count, $p_count);
            if($count_arr){
                setcaches($key_1,$count_arr,$time);
                setcaches($key_total_1,$total_1,$time);
            }
        }

        $mine_count = $count_arr[0];

        $key_P = 'CrontabRewardVideoDeal7P';
        $p=getcaches($key_P);
        if(!$p){
            $p = 1;
            setcaches($key_P,$p,$time);
        }

        $jump = $p_count*($p-1);

        $list_mine=Db::name("user_mine_machine")->where(["level"=>7,"status"=>1])->order('id asc')->limit($jump,$mine_count)->select();

        $this->deal_mine($list_mine,$min_id,$max_id,$total_1);

        setcaches($key_P,$p+1,$time);

        if(count($count_arr)>1){
            unset($count_arr[0]);
            $count_arr = array_values($count_arr);
            setcaches($key_1,$count_arr,3600);
        }else{
            delcache($key_1);
            delcache($key_P);
            delcache($key_total_1);
            delcache($key);
            $this->deal_end($key_r,$max_id,$type);
            exit;
        }

    }

    protected function deal_mine($list_mine,$min_id,$max_id,$total_1)
    {
        $nowtime = time();
        $time = 3600;
        foreach($list_mine as $k=>$v){
            $insert_popo=[
                'type'=>'1',
                'action'=>5,
                'uid'=>$v['uid'],
                'fromid'=>$v['id'],
                'actionid'=>$min_id,
                'lastactionid'=>$max_id,
                'nums'=>$total_1,
                'total'=>$total_1,
                'addtime'=>$nowtime,
            ];
            Db::name("user_popopoolrecord")->insert($insert_popo);
            Db::name("user")->where(['id'=>$v['uid']])->inc('popo_pool',$total_1)->inc('popo_accumulative', $total_1)->update();

            // 给上三代分2%
            $agent =Db::name("agent")
                ->where(['uid'=>$v['uid']])
                ->find();
            if($agent){
                $relation_chain = explode(',',$agent['relation_chain']);
                $relation_chain_count = count($relation_chain);
                for ($i=0;$i<3;$i++){
                    if(isset($relation_chain[$relation_chain_count-$i-1])){
                        $promotion_uid = $relation_chain[$relation_chain_count-$i-1];
                        // 检索用户是否有矿机
                        $key_user_mine = 'CrontabRewardDealMine_'.$promotion_uid;
                        $find_mine_user=getcaches($key_user_mine);
                        if(!$find_mine_user){
                            $find_mine_user = Db::name("user_mine_machine")->where(['uid'=>$promotion_uid])->find();
                            if($find_mine_user){
                                $find_mine_user = 1;
                            }else{
                                $find_mine_user = 2;
                            }
                            setcaches($key_user_mine,$find_mine_user,$time);
                        }
                        if($find_mine_user==1){
                            $total_1_dividend = $total_1*0.02;
                            $insert_popo_dividend=[
                                'type'=>'1',
                                'action'=>1,
                                'uid'=>$promotion_uid,
                                'actionid'=>$min_id,
                                'lastactionid'=>$max_id,
                                'nums'=>$total_1_dividend,
                                'total'=>$total_1_dividend,
                                'addtime'=>$nowtime,
                            ];
                            Db::name("user_popopoolrecord_dividend")->insert($insert_popo_dividend);
                            Db::name("user")->where(['id'=>$promotion_uid])->inc('popo_pool',$total_1_dividend)->inc('popo_share', $total_1_dividend)->update();
                        }
                    }
                }
            }
        }

    }

    /**
     * @param $list_mine
     * @param $min_id
     * @param $max_id
     * @param $total_1
     * @param string $key_P
     * @param $p
     * @param int $time
     * @param $count_arr
     * @param string $key_1
     * @param $deal_arr
     * @param $key
     * @param string $key_total_1
     * @return void
     */
    protected function extracted($list_mine, $min_id, $max_id, $total_1, string $key_P, $p, int $time, $count_arr, string $key_1, $deal_arr, $key, string $key_total_1): void
    {
        $this->deal_mine($list_mine, $min_id, $max_id, $total_1);

        setcaches($key_P, $p + 1, $time);

        if (count($count_arr) > 1) {
            unset($count_arr[0]);
            $count_arr = array_values($count_arr);
            setcaches($key_1, $count_arr, 3600);
        } else {
            unset($deal_arr[0]);
            $deal_arr = array_values($deal_arr);
            setcaches($key, $deal_arr, 3600);
            delcache($key_1);
            delcache($key_P);
            delcache($key_total_1);
        }
        $configpub = getConfigPub();
        sleep(1);
        header('Location: ' . $configpub['site'] . $_SERVER['REQUEST_URI']);
        exit;
    }


}