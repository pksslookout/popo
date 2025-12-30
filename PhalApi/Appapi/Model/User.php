<?php

class Model_User extends PhalApi_Model_NotORM {

    public function getBaseInfoCount($uid) {
        $info['video_count']=getVideoStatusCount($uid);
        $info['like_video_count']=getLikeVideoStatusCount($uid);
        $info['collect_count']=getCollectVideoStatusCount($uid);
        return $info;
    }

    public function getUserLevel($uid) {
        $key="getUserLevelNew_v2_".$uid;
        $info=getcaches($key);
        if(!$info) {
            $info = DI()->notorm->user
                ->select("consumption,votestotal,team_level,islive")
                ->where('id=?', $uid)
                ->fetchOne();
            if ($info) {
                $level_data = getLevel2($info['consumption']);
                $info['level'] = $level_data['levelid'];
                $info['level_parent'] = $level_data['parent'];
                $level = 1;
                for ($i = 1; $i <= 10; $i++) {
                    if ($info['level'] <= 10 * $i) {
                        $level = $i;
                        break;
                    }
                }

                if ($info['consumption'] < 1000) {
                    $info['level'] = '0';
                }
                $info['level_thumb'] = get_upload_path('images/new_level_v2/level_' . $level . '@2x.png');
                $info['level_bg_thumb'] = get_upload_path('images/new_level_v2/level_bg_' . $level . '@2x.png');

                if ($info['islive'] == 1 && $info['votestotal'] > 0) {
                    $info['level_anchor'] = getLevelAnchor($info['votestotal']);
                    $info['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_' . $info['level_anchor'] . '@3x.png');
                } else {
                    $info['level_anchor'] = '0';
                    $info['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_1@3x.png');
                }

                $info['vip'] = getUserVip($uid);
                $info['vip_thumb'] = get_upload_path('images/new_level_v2/VIP1@2x.png');

                $info['level_team'] = $info['team_level'];
                if ($info['level_team'] > 0) {
                    $info['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_' . $info['level_team'] . '@3x.png');
                } else {
                    $info['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_1@3x.png');
                }

                $info['level_family'] = '0';
                $family_user_count = 0;
                // 公会信息获取
                $family = DI()->notorm->family->select('id,votes')->where('uid = ?', $uid)->fetchOne();
                if (!$family) {
                    $family = DI()->notorm->family_user->select('familyid')->where('uid = ?', $uid)->fetchOne();
                    if ($family) {
                        $family = DI()->notorm->family->select('id,votes')->where('id = ?', $family['familyid'])->fetchOne();
                        $family_user_count = DI()->notorm->family_user->select('familyid')->where('familyid = ?', $family['familyid'])->count();
                    }
                }
                if ($family && $family_user_count > 10) {
                    $level_family = getLevelFamily($family['votestotal']);
                    $info['level_family'] = $level_family;
                }
                if ($info['level_family'] != 0) {
                    $info['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_' . $info['level_family'] . '@3x.png');
                } else {
                    $info['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_1@3x.png');
                }
                unset($info['consumption']);
                unset($info['votestotal']);
                unset($info['islive']);
//                unset($info['team_level']);
            }
            setcaches($key,$info,60*3);
        }
        return $info;
    }
    /* 用户全部信息 */
    public function getBaseInfo($uid) {
        $info=DI()->notorm->user
            ->select("id,user_login,user_nicename,user_email,islive,mobile,avatar,bg_img,avatar_thumb,sex,team_level,signature,coin,votes,consumption,votestotal,province,city,birthday,location")
            ->where('id=?',$uid)
            ->fetchOne();
        if($info) {
//            $lang=GL();
//            if($lang!='zh_cn'){
//                $info['user_nicename'] = str_replace('POPO用户', 'POPO-User-', $info['user_nicename']);
//            }
            $user_information=DI()->notorm->user_information
                ->select("bnb_adr,user_pay_pass")
                ->where('id=?',$uid)
                ->fetchOne();
            if($user_information['user_pay_pass']){
                $info['user_pay_pass'] = 1;
            }else{
                $info['user_pay_pass'] = 0;
            }
            $info['bg_img'] = get_upload_path($info['bg_img']);
            $info['avatar'] = get_upload_path($info['avatar']);
            $info['avatar_thumb'] = get_upload_path($info['avatar_thumb']);
            $info['level'] = getLevel($info['consumption']);
            $level = 1;
            for ($i = 1; $i <= 10; $i++) {
                if ($info['level'] <= 10 * $i) {
                    $level = $i;
                    break;
                }
            }

            if ($info['consumption'] < 1000) {
                $info['level'] = '0';
            }
            $info['level_thumb'] = get_upload_path('images/new_level_v2/level_' . $level . '@2x.png');
            $info['level_bg_thumb'] = get_upload_path('images/new_level_v2/level_bg_' . $level . '@2x.png');
            if ($info['islive'] == 1 && $info['votestotal'] > 0) {
                $info['level_anchor'] = getLevelAnchor($info['votestotal']);
                $info['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_' . $info['level_anchor'] . '@3x.png');
            } else {
                $info['level_anchor'] = '0';
                $info['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_1@3x.png');
            }
            $info['lives'] = getLives($uid);
            $info['follows'] = getFollows($uid);
            $info['fans'] = getFans($uid);
            $info['likes'] = getLikes($uid);
            $info['video_count'] = getVideoStatusCount($uid);
            $info['like_video_count'] = getLikeVideoStatusCount($uid);
            $info['collect_count'] = getCollectVideoStatusCount($uid);
            $info['vip'] = getUserVip($uid);
            $info['vip_thumb'] = get_upload_path('images/new_level_v2/VIP1@2x.png');

            $info['level_team'] = $info['team_level'];
            if ($info['level_team'] > 0) {
                $info['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_' . $info['level_team'] . '@3x.png');
            } else {
                $info['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_1@3x.png');
            }
            $info['liang'] = getUserLiang($uid);
            $info['bnb_adr'] = $user_information['bnb_adr'];

            if ($info['birthday']) {
                $info['birthday'] = date('Y-m-d', $info['birthday']);
                $info['age'] = calculateAge($info['birthday']);
            } else {
                $info['birthday'] = '';
                $info['age'] = '';
            }
            $info['level_family'] = 0;
            $family_user_count = 0;
            // 公会信息获取
            $family = DI()->notorm->family->select('id,votes')->where('uid = ?', $uid)->fetchOne();
            if (!$family) {
                $family = DI()->notorm->family_user->select('familyid')->where('uid = ?', $uid)->fetchOne();
                if ($family) {
                    $family = DI()->notorm->family->select('id,votes')->where('id = ?', $family['familyid'])->fetchOne();
                    $family_user_count = DI()->notorm->family_user->select('familyid')->where('familyid = ?', $family['familyid'])->count();
                }
            }
            if($family&&$family_user_count>3) {
                $level_family = getLevelFamily($family['votestotal']);
                $info['level_family'] = $level_family;
            }
            if ($info['level_family'] != 0) {
                $info['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_' . $info['level_family'] . '@3x.png');
            } else {
                $info['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_1@3x.png');
            }

        }


        return $info;
    }

    /* 判断昵称是否重复 */
    public function checkName($uid,$name){
        $isexist=DI()->notorm->user
            ->select('id')
            ->where('id!=? and user_nicename=?',$uid,$name)
            ->fetchOne();
        if($isexist){
            return 0;
        }else{
            return 1;
        }
    }

    /* 修改信息 */
    public function userUpdate($uid,$fields){

        /* 清除缓存 */
        delCache("userinfo_".$uid);

        return DI()->notorm->user
            ->where('id=?',$uid)
            ->update($fields);
    }

    /* 修改信息 */
    public function userUpdateMobile($uid,$fields){

        $isexist=DI()->notorm->user
            ->where('mobile=?',$fields['mobile'])
            ->count();
        if($isexist&&$isexist>=10){
            return false;
        }

        /* 清除缓存 */
        delCache("userinfo_".$uid);

        return DI()->notorm->user
            ->where('id=?',$uid)
            ->update($fields);
    }

    /* 修改密码 */
    public function updatePass($uid,$old_pass,$pass){
        $old_pass=setPass($old_pass);
		$userinfo=DI()->notorm->user
					->select("user_pass")
					->where('id=?',$uid)
					->fetchOne();
        if($userinfo['user_pass']!=$old_pass){
//            return 1003;
        }
        $newpass=setPass($pass);
        return DI()->notorm->user
            ->where('id=?',$uid)
            ->update( array( "user_pass"=>$newpass ) );
    }

    /* 修改密码 */
    public function updatePayPass($uid,$pass){
//		$userinfo=DI()->notorm->user
//					->select("user_pass")
//					->where('id=?',$uid)
//					->fetchOne();
//		$newpass=setPass($pass);
        return DI()->notorm->user_information
            ->where('id=?',$uid)
            ->update( array( "user_pay_pass"=>$pass ) );
    }

    /* 修改密码 */
    public function updateBnbAdr($uid,$bnb_adr){
        return DI()->notorm->user_information
            ->where('id=?',$uid)
            ->update( array( "bnb_adr"=>$bnb_adr ) );
    }

    /* 我的钻石 */
    public function getBalance($uid){
        $info = DI()->notorm->user
            ->select("coin,consumption,conversion,votes,votesearnings")
            ->where('id=?',$uid)
            ->fetchOne();

        $yesterdayMidnightTimestamp = strtotime('yesterday');
        $todayMidnightTimestamp = strtotime('today');

        $info['yesterday_earnings'] = (float)DI()->notorm->user_voterecord->where("action in (1,14) and uid=$uid and addtime > $yesterdayMidnightTimestamp and addtime < $todayMidnightTimestamp")->sum('votes');
        $info['today_earnings'] = (float)DI()->notorm->user_voterecord->where("action in (1,14) and uid=$uid and addtime > $todayMidnightTimestamp")->sum('votes');

//        $info['coin'] = dealPrice($info['coin']);
//        $info['consumption'] = dealPrice($info['consumption']);
//        $info['conversion'] = dealPrice($info['conversion']);
        $info['votesearnings'] = dealPrice($info['votesearnings']);
        $info['votes'] = dealPrice($info['votes']);
        $info['yesterday_earnings'] = dealPrice($info['yesterday_earnings']);
        $info['today_earnings'] = dealPrice($info['today_earnings']);

        return 	$info;
    }

    /* 我的钻石 */
    public function getMyUsdtInfo($uid){
        $user = DI()->notorm->user
            ->select("usdt")
            ->where('id=?',$uid)
            ->fetchOne();
        $user_information = DI()->notorm->user_information
            ->select("usdt_charge,usdt_forward")
            ->where('id=?',$uid)
            ->fetchOne();
        $info['usdt_forward'] = dealPrice($user['usdt_forward']);
        $info['usdt_charge'] = dealPrice($user_information['usdt_charge']);
        $info['usdt'] = dealPrice($user_information['usdt']);

        return 	$info;
    }

    /* 充值规则 */
    public function getChargeRules(){

        $rules= DI()->notorm->charge_rules
            ->select('id,coin,coin_ios,money,product_id,give')
            ->order('list_order asc')
            ->fetchAll();

        return 	$rules;
    }

    /* vip充值规则 */
    public function getVipChargeRules(){

        $rules= DI()->notorm->vip_charge_rules
            ->select('id,name,name_en,money,days,coin')
            ->order('list_order asc')
            ->fetchAll();
        $lang = GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('vip_charge_rules', 'name', $lang);
        }
        foreach($rules as $k=>$v){
            if($lang=='en'){
                $rules[$k]['name']=$v['name_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $rules[$k]['name']=$translate[$v['id']];
                    }
                }
            }
        }

        return 	$rules;
    }

    /* 我的收益 */
    public function getProfit($uid){
        $info= DI()->notorm->user
            ->select("votes,votestotal")
            ->where('id=?',$uid)
            ->fetchOne();

        $config=getConfigPri();

        $configpub=getConfigPub();

        //提现比例
        $name_votes=$configpub['name_votes'];
        $cash_rate=$config['cash_rate'];
        $cash_start=$config['cash_start'];
        $cash_end=$config['cash_end'];
        $cash_max_times=$config['cash_max_times'];
        $cash_take=$config['cash_take'];
        //剩余票数
        $votes=$info['votes'];

        if(!$cash_rate){
            $total='0';
        }else{
            //总可提现数
            $total=(string)(floor($votes/$cash_rate)*(100-$cash_take)/100);
        }

        if($cash_max_times){
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放，每月只可提现'.$cash_max_times.'次';
            $tips=T('每月{cash_start}-{cash_end}号可进行提现申请，每月只可提现{cash_max_times}次',['$cash_start'=>$cash_start,'cash_end'=>$cash_end,'cash_max_times'=>$cash_max_times]);
        }else{
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放';
            $tips=T('每月{cash_start}-{cash_end}号可进行提现申请',['$cash_start'=>$cash_start,'cash_end'=>$cash_end]);
        }

        $rs=array(
            "name_votes"=>$name_votes,
            "votes"=>$votes,
            "votestotal"=>$info['votestotal'],
            "total"=>$total,
            "cash_rate"=>$cash_rate,
            "cash_take"=>$cash_take,
            "tips"=>$tips,
        );
        return $rs;
    }

    /* 我的收益 */
    public function getUsdtForward($uid){
        $info= DI()->notorm->user
            ->select("usdt")
            ->where('id=?',$uid)
            ->fetchOne();

        $config=getConfigPri();

        //提现比例
        $cash_rate=$config['usdt_rate'];
        $cash_start=$config['usdt_start'];
        $cash_end=$config['usdt_end'];
        $cash_max_times=$config['usdt_max_times'];
        $cash_take=$config['usdt_take'];
        //剩余usdt
        $usdt=$info['usdt'];

        if(!$cash_rate){
            $total='0';
        }else{
            //总可提现数
            $total=($usdt/$cash_rate)*(100-$cash_take)/100;
        }

        if($cash_max_times){
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放，每月只可提现'.$cash_max_times.'次';
            $tips=T('每月{cash_start}-{cash_end}号可进行提现申请，每月只可提现{cash_max_times}次',['$cash_start'=>$cash_start,'cash_end'=>$cash_end,'cash_max_times'=>$cash_max_times]);
        }else{
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放';
            $tips=T('每月{cash_start}-{cash_end}号可进行提现申请',['cash_start'=>$cash_start,'cash_end'=>$cash_end]);
        }

        return array(
            "usdt"=>dealPrice($usdt,6),
            "total"=>dealPrice($total,6),
            "cash_rate"=>$cash_rate,
            "cash_take"=>$cash_take,
            "tips"=>$tips,
            "tips_one"=>T('*合约信息***97955'),
            "tips_two"=>T('*请勿直接提现至众筹或ICO地址。否则将无法收到众筹或者ICO发放的代币。'),
            "tips_three"=>T('*请勿与受制裁实体进行交易。'),
        );
    }

    /* 红包收益 */
    public function getRedProfit($uid){
        $info= DI()->notorm->user
            ->select("red_votes,votestotal")
            ->where('id=?',$uid)
            ->fetchOne();

        $config=getConfigPri();

        $configpub=getConfigPub();

        //提现比例
        $name_votes=$configpub['name_votes'];
        $cash_rate=$config['red_cash_rate'];
        $cash_start=$config['cash_start'];
        $cash_end=$config['cash_end'];
        $cash_max_times=$config['cash_max_times'];
        $cash_take=$config['cash_take'];
        //剩余票数
        $votes=$info['red_votes'];

        if(!$cash_rate){
            $total='0';
        }else{
            //总可提现数
            $total=(string)(floor($votes/$cash_rate)*(100-$cash_take)/100);
        }

        if($cash_max_times){
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放，每月只可提现'.$cash_max_times.'次';
            $tips=T('每月{cash_start}-{cash_end}号可进行提现申请，每月只可提现{cash_max_times}次',['$cash_start'=>$cash_start,'cash_end'=>$cash_end,'cash_max_times'=>$cash_max_times]);
        }else{
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放';
            $tips=T('每月{cash_start}-{cash_end}号可进行提现申请',['$cash_start'=>$cash_start,'cash_end'=>$cash_end]);
        }

        $rs=array(
            "name_votes"=>$name_votes,
            "votes"=>$votes,
            "votestotal"=>$info['votestotal'],
            "total"=>$total,
            "cash_rate"=>$cash_rate,
            "cash_take"=>$cash_take,
            "tips"=>$tips,
        );
        return $rs;
    }
    /* 提现  */
    public function setCash($data){

        $nowtime=time();

        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $cashvote=$data['cashvote'];

        $config=getConfigPri();
        $cash_start=$config['cash_start'];
        $cash_end=$config['cash_end'];
        $cash_max_times=$config['cash_max_times'];

        $day=(int)date("d",$nowtime);

        if($day < $cash_start || $day > $cash_end){
            return 1005;
        }

        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");

        if($cash_max_times){
            $isexist=DI()->notorm->cash_record
                ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                ->count();
            if($isexist >= $cash_max_times){
                return 1006;
            }
        }

        $isrz=DI()->notorm->user_auth
            ->select("status")
            ->where('uid=?',$uid)
            ->fetchOne();
        if(!$isrz || $isrz['status']!=1){
            return 1003;
        }

        /* 钱包信息 */
        $accountinfo=DI()->notorm->cash_account
            ->select("*")
            ->where('id=? and uid=?',$accountid,$uid)
            ->fetchOne();

        if(!$accountinfo){

            return 1007;
        }


        //提现比例
        $cash_rate=$config['cash_rate'];

        /*提现抽成比例*/
        $cash_take=$config['cash_take'];

        /* 最低额度 */
        $cash_min=$config['cash_min'];

        //提现钱数
        $cash_money=floor($cashvote/$cash_rate);

        if($cash_money < $cash_min){
            return 1004;
        }

        $cashvotes=$cash_money*$cash_rate;


        $ifok=DI()->notorm->user
            ->where('id = ? and votes>=?', $uid,$cashvotes)
            ->update(array('votes' => new NotORM_Literal("votes - {$cashvotes}")) );
        if(!$ifok){
            return 1001;
        }

        //平台抽成后最终的钱数
        $money_take=$cash_money*(1-$cash_take*0.01);
        $money=number_format($money_take,2,".","");

        $data=array(
            "uid"=>$uid,
            "cash_money"=>$cash_money,
            "cash_take"=>$cash_take,
            "money"=>$money,
            "votes"=>$cashvotes,
            "orderno"=>$uid.'_'.$nowtime.rand(100,999),
            "status"=>0,
            "addtime"=>$nowtime,
            "uptime"=>$nowtime,
            "type"=>$accountinfo['type'],
            "account_bank"=>$accountinfo['account_bank'],
            "account"=>$accountinfo['account'],
            "name"=>$accountinfo['name'],
        );

        $rs=DI()->notorm->cash_record->insert($data);
        if(!$rs){
            return 1002;
        }

        return $rs;
    }
    /* 提现USDT  */
    public function forwardChainUsdt($uid,$adr,$chainType,$number,$user_pay_pass){

        $nowtime=time();


        $config=getConfigPri();
        $usdt_start=$config['usdt_start'];
        $usdt_end=$config['usdt_end'];
        $usdt_max_times=$config['usdt_max_times'];
        /*提现抽成比例*/
        $usdt_take=$config['usdt_take'];
        if($number<=$usdt_take){
            return 1004;
        }

        $day=(int)date("d",$nowtime);

        if($day < $usdt_start || $day > $usdt_end){
            return 1005;
        }

        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");

        if($usdt_max_times){
            $isexist=DI()->notorm->usdt_record
                ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                ->count();
            if($isexist >= $usdt_max_times){
                return 1006;
            }
        }

        $isrz=DI()->notorm->user_auth
            ->select("status")
            ->where('uid=?',$uid)
            ->fetchOne();
        if(!$isrz || $isrz['status']!=1){
            return 1003;
        }

        /* 钱包信息 */
//		$accountinfo=DI()->notorm->cash_account
//				->select("*")
//				->where('id=? and uid=?',$accountid,$uid)
//				->fetchOne();
//
//        if(!$accountinfo){
//
//            return 1007;
//        }


        //提现比例
        $usdt_rate=$config['usdt_rate'];


        /* 最低额度 */
        $usdt_min=$config['usdt_min'];

        //提现钱数
        $usdt_money=$number/$usdt_rate;

        if($usdt_money < $usdt_min){
            return 1004;
        }

        $usdt=$usdt_money*$usdt_rate;

        try {
            DI()->notorm->beginTransaction('db_appapi');

            $user_information=DI()->notorm->user_information
                ->select("user_pay_pass")
                ->where('id=?',$uid)
                ->fetchOne();

            if(empty($user_information['user_pay_pass'])){
                DI()->notorm->rollback('db_appapi');
                return 1032;
            }

            if($user_information['user_pay_pass']!=$user_pay_pass){
                DI()->notorm->rollback('db_appapi');
                return 1031;
            }

            $ifok = DI()->notorm->user
                ->where('id = ? and usdt >= ?', $uid, $usdt)
                ->update(array('usdt' => new NotORM_Literal("usdt - {$usdt}")));
            if (!$ifok) {
                DI()->notorm->rollback('db_appapi');
                return 1001;
            }
            DI()->notorm->user_information
                ->where('id = ?', $uid)
                ->update(array('usdt_forward' => new NotORM_Literal("usdt_forward + {$usdt}")));

            //平台抽成后最终的钱数
            $money=$usdt-$usdt_take;
            $money=number_format($money,2,".","");

            $data = array(
                "uid" => $uid,
                "usdt_money" => $number,
                "usdt_take" => $usdt_take,
                "money" => $money,
                "chain_type" => $chainType,
                "trade_no" => $adr,
                "orderno" => $uid . '_' . $nowtime . rand(100, 999),
                "status" => 0,
                "addtime" => $nowtime,
                "uptime" => $nowtime,
            );

            $rs = DI()->notorm->usdt_record->insert($data);
            if (!$rs) {
                DI()->notorm->rollback('db_appapi');
                return 1002;
            }
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }


        return $rs;
    }
    /* 充值vip  */
    public function setVipBalance($data){

        $rs['code'] = 400;
        $rs['msg'] = T('服务暂停');
        return $rs;
        $nowtime=time();

        $uid=$data['uid'];
        $rules_id=$data['rules_id'];

        //获取vip充值规则
        $rulesInfo = DI()->notorm->vip_charge_rules
            ->select('id,name,name_en,money,days,coin')
            ->where('id=?',$rules_id)
            ->fetchOne();
        // 余额查询
        $userInfo = DI()->notorm->user
            ->select('coin,user_nicename')
            ->where('id=?',$uid)
            ->fetchOne();

        if($rulesInfo['coin'] > $userInfo['coin']){
            return 1004;
        }

        $ifok=DI()->notorm->user
            ->where('id = ?', $uid)
            ->update(array('coin' => new NotORM_Literal("coin - {$rulesInfo['coin']}")) );
        if(!$ifok){
            return 1001;
        }

        //更新vip到期时间
        $userVipInfo = DI()->notorm->vip_user
            ->select('endtime')
            ->where('uid=?',$uid)
            ->fetchOne();
        $endtime = $nowtime+($rulesInfo['days']*86400);
        if($userVipInfo){
            if($userVipInfo['endtime']>$nowtime){
                $endtime = $userVipInfo['endtime']+($rulesInfo['days']*86400);

            }
            DI()->notorm->vip_user
                ->where('uid = ?', $uid)
                ->update(array('endtime' => $endtime));
        }else{
            DI()->notorm->vip_user->insert(['endtime' => $endtime, 'uid' => $uid, 'addtime' => $nowtime]);
        }

        // 清除缓存
        $key='vip_'.$uid;
        delcache($key);

        $type='0';
        $action='24';
        $giftid=0;
        $giftcount=0;
        $total=$rulesInfo['coin'];
        $showid=0;
        $addtime=$nowtime;


        $insert=array("type"=>$type,"action"=>$action,"uid"=>$uid,"touid"=>$uid,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime );
        $rs=DI()->notorm->user_coinrecord->insert($insert);
        if(!$rs){
            return 1002;
        }

        $insert=array(
            "uid"=>$uid,
            "touid"=>$uid,
            "orderno"=>$uid.'_'.$nowtime.rand(100,999),
            "user_nicename"=>$userInfo['user_nicename'],
            "money"=>0,
            "days"=>$rulesInfo['days'],
            "coin"=>$rulesInfo['coin'],
            "ambient"=>0,
            "type"=>0,
            "status"=>1,
            "addtime"=>$nowtime
        );
        $rs=DI()->notorm->vip_charge_user->insert($insert);
        if(!$rs){
            return 1002;
        }
        return $rs;
    }
    /* 提现  */
    public function setRedCash($data){

        $nowtime=time();

        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $cashvote=$data['cashvote'];

        $config=getConfigPri();
        $cash_start=$config['cash_start'];
        $cash_end=$config['cash_end'];
        $cash_max_times=$config['cash_max_times'];

        $day=(int)date("d",$nowtime);

        if($day < $cash_start || $day > $cash_end){
            return 1005;
        }

        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");

        if($cash_max_times){
            $isexist=DI()->notorm->cash_record
                ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                ->count();
            if($isexist >= $cash_max_times){
                return 1006;
            }
        }

        $isrz=DI()->notorm->user_auth
            ->select("status")
            ->where('uid=?',$uid)
            ->fetchOne();
        if(!$isrz || $isrz['status']!=1){
            return 1003;
        }

        /* 钱包信息 */
        $accountinfo=DI()->notorm->cash_account
            ->select("*")
            ->where('id=? and uid=?',$accountid,$uid)
            ->fetchOne();

        if(!$accountinfo){

            return 1007;
        }

        //提现比例
        $cash_rate=$config['red_cash_rate'];

        /*提现抽成比例*/
        $cash_take=$config['cash_take'];

        /* 最低额度 */
        $cash_min=$config['cash_min'];

        //提现钱数
        $cash_money=floor($cashvote/$cash_rate);

        if($cash_money < $cash_min){
            return 1004;
        }

        $cashvotes=$cash_money*$cash_rate;


        $ifok=DI()->notorm->user
            ->where('id = ? and red_votes>=?', $uid,$cashvotes)
            ->update(array('red_votes' => new NotORM_Literal("votes - {$cashvotes}")) );
        if(!$ifok){
            return 1001;
        }

        //平台抽成后最终的钱数
        $money_take=$cash_money*(1-$cash_take*0.01);
        $money=number_format($money_take,2,".","");

        $data=array(
            "uid"=>$uid,
            "money"=>$money,
            "cash_money"=>$cash_money,
            "cash_take"=>$cash_take,
            "votes"=>$cashvotes,
            "orderno"=>$uid.'_'.$nowtime.rand(100,999),
            "status"=>0,
            "addtime"=>$nowtime,
            "uptime"=>$nowtime,
            "type"=>$accountinfo['type'],
            "account_bank"=>$accountinfo['account_bank'],
            "account"=>$accountinfo['account'],
            "name"=>$accountinfo['name'],
            "cash_type"=>1,
        );

        $rs=DI()->notorm->cash_record->insert($data);
        if(!$rs){
            return 1002;
        }

        return $rs;
    }

    /* 关注 */
    public function setAttent($uid,$touid){
        $isexist=DI()->notorm->user_attention
            ->select("*")
            ->where('uid=? and touid=?',$uid,$touid)
            ->fetchOne();
        if($isexist){
            if($isexist['status']==1){
                $result=DI()->notorm->user_attention->where("uid=? and touid=?",$uid,$touid)->update(array("status"=>0,"updatetime"=>time()));
                $data=[
                    'type'=>'3',
                    'nums'=>'1',
                ];
                dailyTasks($uid,$data);
                if($result!==false){
                    return 0;
                }else{
                    return 0;
                }
            }else{
                DI()->notorm->user_black
                    ->where('uid=? and touid=?',$uid,$touid)
                    ->delete();
                $result=DI()->notorm->user_attention->where("uid=? and touid=?",$uid,$touid)->update(array("status"=>1,"is_read"=>0,"updatetime"=>time()));
//
                $data=[
                    'type'=>'3',
                    'nums'=>'1',
                ];
                dailyTasks($uid,$data);
//                DI()->redis->select(1);
//                DI()->redis->incr('new_message_fans_count_'.$touid);

//                if($result!==false){
//                    return 0;
//                }else{
//                    return 0;
//                }
            }
//			DI()->notorm->user_attention
//				->where('uid=? and touid=?',$uid,$touid)
//				->delete();
            return 1;
        }else{
            DI()->notorm->user_black
                ->where('uid=? and touid=?',$uid,$touid)
                ->delete();
            DI()->notorm->user_attention
                ->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));
            $data=[
                'type'=>'3',
                'nums'=>'1',
            ];
            dailyTasks($uid,$data);
            return 1;
        }
    }

    /* 拉黑 */
    public function setBlack($uid,$touid){
        $isexist=DI()->notorm->user_black
            ->select("*")
            ->where('uid=? and touid=?',$uid,$touid)
            ->fetchOne();
        if($isexist){
            DI()->notorm->user_black
                ->where('uid=? and touid=?',$uid,$touid)
                ->delete();
            return 0;
        }else{
            DI()->notorm->user_attention
                ->where('uid=? and touid=?',$uid,$touid)
                ->delete();
            DI()->notorm->user_black
                ->insert(array("uid"=>$uid,"touid"=>$touid));

            return 1;
        }
    }

    /* 关注列表 */
    public function getFollowsList($uid,$touid,$p,$user_nicename){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        if(!empty($user_nicename)){
            $where = 'and u.user_nicename like "%'.$user_nicename.'%" ';
        }else{
            $where = '';
        }
        $sql = 'SELECT cua.touid AS id, u.user_nicename, u.avatar, u.avatar_thumb, u.sex, u.signature, u.city '
            . 'FROM cmf_user_attention AS cua INNER JOIN cmf_user AS u '
            . 'ON cua.touid = u.id '
            . 'WHERE cua.uid = '.$touid.' and cua.status=1 '.$where
            . 'ORDER BY cua.addtime DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $list = $this->getORM()->queryAll($sql);
        if($list){
            foreach($list as $k=>$v){
                $list[$k]['isattention']=isAttention($uid,$v['id']);
                $list[$k]['avatar']=get_upload_path($v['avatar']);
                $list[$k]['avatar_thumb']=get_upload_path($v['avatar_thumb']);
                $list[$k]['fans']=getFans($v['touid']);
            }
        }
        return $list;
    }

    /* 点赞我的人列表 */
    public function getLikesList($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        //获取视频点赞列表
        $sql = 'SELECT v.id AS videoid, v.thumb AS video_thumb, vl.uid, vl.addtime '
            . 'FROM cmf_video AS v INNER JOIN cmf_video_like AS vl '
            . 'ON v.id = vl.videoid '
            . 'WHERE v.uid = '.$uid.' '
            . 'ORDER BY vl.addtime DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $videoLikeList = $this->getORM()->queryAll($sql, []);
        foreach($videoLikeList as $k=>$v){
            $userInfo=getUserInfo($v['uid'], 1);
            $videoLikeList[$k]['user_nicename']=$userInfo['user_nicename'];
            $videoLikeList[$k]['video_thumb']=get_upload_path($v['video_thumb']);
            $videoLikeList[$k]['avatar']=get_upload_path($userInfo['avatar']);
            $videoLikeList[$k]['addtime']=datetime($v['addtime']);
            $videoLikeList[$k]['title']='赞了你的视频';
        }
        //获取视频评论点赞列表
        $sql = 'SELECT v.id AS videoid, v.thumb AS video_thumb, vcl.uid, vcl.addtime, vcl.commentid '
            . 'FROM cmf_video AS v INNER JOIN cmf_video_comments_like AS vcl '
            . 'ON v.id = vcl.videoid '
            . 'WHERE v.uid = '.$uid.' '
            . 'ORDER BY vcl.id DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $videoCommentsLikeList = $this->getORM()->queryAll($sql, []);
        foreach($videoCommentsLikeList as $k=>$v){
            $userInfo=getUserInfo($v['uid'], 1);
            $videoCommentsLikeList[$k]['user_nicename']=$userInfo['user_nicename'];
            $videoCommentsLikeList[$k]['video_thumb']=get_upload_path($v['video_thumb']);
            $videoCommentsLikeList[$k]['avatar']=get_upload_path($userInfo['avatar']);
            $videoCommentsLikeList[$k]['addtime']=datetime($v['addtime']);
            $videoCommentsLikeList[$k]['title']='赞了你的评论';
        }
        $videoList=array_merge($videoLikeList, $videoCommentsLikeList);
        return $videoList;
    }


    /* 我的上热门视频列表 */
    public function getPopularVideoList($uid,$p,$status){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $time = time();
        //获取视频点赞列表
        $sql = 'SELECT p.id,p.price,p.status,p.duration,p.view_counts,p.actual_view_counts,p.videoid,p.return_price,v.title,v.thumb AS video_thumb,p.addtime '
            . 'FROM cmf_popular AS p LEFT JOIN cmf_video AS v '
            . 'ON p.videoid = v.id '
            . 'WHERE p.uid = '.$uid.' and p.status = '.$status.' '
            . 'ORDER BY p.addtime DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $popularVideoList = $this->getORM()->queryAll($sql, []);
        foreach($popularVideoList as $k=>$v){
            $popularVideoList[$k]['video_thumb']=get_upload_path($v['video_thumb']);
            $popularVideoList[$k]['actual_time']=getSeconds($time-$v['addtime']);
            $popularVideoList[$k]['addtime']=date('Y-m-d H:i:s', $v['addtime']);
        }
        return $popularVideoList;
    }

    /* 我的上热门视频列表 */
    public function getPopularLiveList($uid,$p,$status){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $time = time();
        //获取视频点赞列表
        $sql = 'SELECT p.id,p.price,p.price,p.status,p.liveendtime,p.view_people_counts,p.view_counts,p.actual_view_counts,p.livetime,p.return_price,u.avatar AS live_avatar,p.addtime '
            . 'FROM cmf_live_popular AS p LEFT JOIN cmf_user AS u '
            . 'ON p.uid = u.id '
            . 'WHERE p.uid = '.$uid.' and p.status = '.$status.' '
            . 'ORDER BY p.addtime DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $popularLiveList = $this->getORM()->queryAll($sql, []);
        foreach($popularLiveList as $k=>$v){
            $popularLiveList[$k]['live_avatar']=get_upload_path($v['live_avatar']);
            if($v['liveendtime']==0){
                $v['liveendtime'] = $time;
            }
            $view_people_counts = explode('-', $v['view_people_counts']);
            $view_people_counts_0 = $view_people_counts[0];
            $view_people_counts_1 = $view_people_counts[1];
            $actual_exposure_amount = 0;
            if($v['actual_view_counts']<$view_people_counts_0){
                $actual_exposure_amount = $v['actual_view_counts']/$view_people_counts_0*$v['view_counts'];
            }
            if($v['actual_view_counts']>$view_people_counts_1){
                $actual_exposure_amount = $v['actual_view_counts']/$view_people_counts_1*$v['view_counts'];
            }
            if($v['actual_view_counts']<$view_people_counts_1&&$v['actual_view_counts']>$view_people_counts_0){
                $actual_exposure_amount = $v['view_counts'];
            }
            $popularLiveList[$k]['actual_exposure_amount']=(int)$actual_exposure_amount;
            $popularLiveList[$k]['actual_time']=getSeconds($v['liveendtime']-$v['livetime']);
            $popularLiveList[$k]['addtime']=date('Y-m-d H:i:s', $v['addtime']);
            if($v['status']==0){
                $popularLiveList[$k]['actual_time']=T('未开播');
            }
        }
        return $popularLiveList;
    }

    /* @我的人列表 */
    public function getAtsList($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        //获取视频点赞列表
        $sql = 'SELECT vca.videoid, v.thumb AS video_thumb, v.title AS video_title, vca.uid, vca.addtime, vca.commentid '
            . 'FROM cmf_video_comments_at AS vca INNER JOIN cmf_video AS v '
            . 'ON vca.videoid = v.id '
            . 'WHERE vca.touid = '.$uid.' '
            . 'ORDER BY vca.id DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $videoLikeList = $this->getORM()->queryAll($sql, []);
        foreach($videoLikeList as $k=>$v) {
            $userInfo = getUserInfo($v['uid'], 1);
            $videoLikeList[$k]['user_nicename'] = $userInfo['user_nicename'];
            $videoLikeList[$k]['video_thumb'] = get_upload_path($v['video_thumb']);
            $videoLikeList[$k]['avatar'] = get_upload_path($userInfo['avatar']);
            $videoLikeList[$k]['addtime'] = datetime($v['addtime']);
            $videoLikeList[$k]['title'] = T('的评论中@了你');
        }
        return $videoLikeList;
    }

    /* 评论我的人列表 */
    public function getCommentsList($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;

        $sql = 'SELECT vc.id, vc.videoid, v.thumb AS video_thumb, v.title AS video_title, vc.uid, vc.addtime, vc.parentid, vc.commentid, vc.content '
            . 'FROM cmf_video_comments AS vc INNER JOIN cmf_video AS v '
            . 'ON vc.videoid = v.id '
            . 'WHERE vc.touid = '.$uid.' '
            . 'ORDER BY vc.id DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $videoLikeList = $this->getORM()->queryAll($sql, []);
        foreach($videoLikeList as $k=>$v) {
            $userInfo = getUserInfo($v['uid'], 1);
            $videoLikeList[$k]['user_nicename'] = $userInfo['user_nicename'];
            $videoLikeList[$k]['video_thumb'] = get_upload_path($v['video_thumb']);
            $videoLikeList[$k]['avatar'] = get_upload_path($userInfo['avatar']);
            $videoLikeList[$k]['addtime'] = datetime($v['addtime']);
            $like=DI()->notorm->video_comments_like
                ->select("id")
                ->where("uid='{$uid}' and commentid='{$v['id']}'")
                ->fetchOne();
            if($like){
                $videoLikeList[$k]['islike']='1';
            }else{
                $videoLikeList[$k]['islike']='0';
            }
            if($v['parentid']>0){
                $comment=DI()->notorm->video_comments
                    ->select("id,content,uid,commentid,videoid")
                    ->where("id='{$v['parentid']}'")
                    ->fetchOne();
                $videoLikeList[$k]['title'] = T('回复了您的评论');
                $videoLikeList[$k]['comment_info']=$comment;
            }else{
                $videoLikeList[$k]['title'] = T('评论了您的作品');
                $videoLikeList[$k]['comment_info']=[];
            }
        }
        return $videoLikeList;
    }

    /* 粉丝列表 */
    public function getFansList($uid,$touid,$p,$status,$keyword){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        if($status){
            if($keyword){
                $where = ' and u.user_nicename like "%'.$keyword.'%"';
            }else{
                $where = '';
            }
            $sql = 'SELECT u.id, u.user_login, u.user_nicename, u.avatar, u.avatar_thumb, u.sex '
                . 'FROM cmf_user_attention AS ua INNER JOIN cmf_user_attention AS ua2 '
                . 'ON ua.uid = ua2.touid '
                . 'INNER JOIN cmf_user AS u '
                . 'ON ua.uid = u.id '
                . 'WHERE ua.touid = '.$uid.' and ua.status=1 and ua2.status=1'.$where.' '
                . 'ORDER BY ua.addtime DESC '
                . 'LIMIT '.$pnum.' OFFSET '.$start;
            $fansList = $this->getORM()->queryAll($sql, []);
            foreach($fansList as $k=>$v) {
                $fansList[$k]['avatar']=get_upload_path($v['avatar']);
                $fansList[$k]['avatar_thumb']=get_upload_path($v['avatar_thumb']);
            }
            return $fansList;

        }else{
            $touids=DI()->notorm->user_attention
                ->select("uid,addtime")
                ->where("touid='{$touid}' and status=1")
                ->order("addtime desc")
                ->limit($start,$pnum)
                ->fetchAll();
            foreach($touids as $k=>$v){
                $userinfo=getUserInfo($v['uid'], 1);
                if($userinfo){
                    $userinfo['isattention']=isAttention($uid,$v['uid']);
                    $userinfo['fans']=getFans($v['uid']);
                    $userinfo['addtime']=datetime($v['addtime']);
                    $touids[$k]=$userinfo;
                }else{
                    DI()->notorm->user_attention->where('uid=? or touid=?',$v['uid'],$v['uid'])->delete();
                    unset($touids[$k]);
                }

            }
            $touids=array_values($touids);
            return $touids;

        }
    }

    /* 黑名单列表 */
    public function getBlackList($uid,$touid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;
        $touids=DI()->notorm->user_black
            ->select("touid")
            ->where('uid=?',$touid)
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($touids as $k=>$v){
            $userinfo=getUserInfo($v['touid'],1);
            if($userinfo){
                $touids[$k]=$userinfo;
            }else{
                DI()->notorm->user_black->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
                unset($touids[$k]);
            }
        }
        $touids=array_values($touids);
        return $touids;
    }

    /* 直播记录 */
    public function getLiverecord($touid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;
        $record=DI()->notorm->live_record
            ->select("id,uid,nums,starttime,endtime,title,city")
            ->where('uid=?',$touid)
            ->order("id desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($record as $k=>$v){
            $record[$k]['datestarttime']=date("Y.m.d",$v['starttime']);
            $record[$k]['dateendtime']=date("Y.m.d",$v['endtime']);
            $cha=$v['endtime']-$v['starttime'];
            $record[$k]['length']=getSeconds($cha);
        }
        return $record;
    }

    /* 个人主页 */
    public function getUserHome($uid,$touid){
        $info=getUserInfo($touid);

        $info['follows']=(string)getFollows($touid);
        $info['fans']=(string)getFans($touid);
        $info['likes']=(string)getLikes($touid);
        $info['isattention']=(string)isAttention($uid,$touid);
        $info['isblack']=(string)isBlack($uid,$touid);
        $info['isblack2']=(string)isBlack($touid,$uid);

        /* 直播状态 */
        $islive='0';
        $isexist=DI()->notorm->live
            ->select('uid')
            ->where('uid=? and islive=1',$touid)
            ->fetchOne();
        if($isexist){
            $islive='1';
        }
        $info['islive']=$islive;

        /* 贡献榜前三 */
        $rs=array();
        $rs=DI()->notorm->user_coinrecord
            ->select("uid,sum(totalcoin) as total")
            ->where('action=1 and touid=?',$touid)
            ->group("uid")
            ->order("total desc")
            ->limit(0,3)
            ->fetchAll();
        foreach($rs as $k=>$v){
            $userinfo=getUserInfo($v['uid'],1);
            $rs[$k]['avatar']=$userinfo['avatar'];
        }
        $info['contribute']=$rs;

        /* 视频数 */
        if($uid==$touid){  //自己的视频（需要返回视频的状态前台显示）
            $where=" uid={$uid} and isdel='0' and status=1";
        }else{  //访问其他人的主页视频
            $videoids_s=getVideoBlack($uid);
            $where="id not in ({$videoids_s}) and uid={$touid} and isdel='0' and status=1";
        }

        $videonums=DI()->notorm->video
            ->where($where)
            ->count();
        if(!$videonums){
            $videonums=0;
        }

        $info['videonums']=(string)$videonums;

        //喜欢的视频数量
        $like_video_count = getLikeVideoStatusCount($touid);
        $info['likevideonums']=(string)$like_video_count;

        /* 动态数 */
        if($uid==$touid){  //自己的动态（需要返回动态的状态前台显示）
            $whered=" uid={$uid} and isdel='0' and status=1";
        }else{  //访问其他人的主页动态
            $whered=" uid={$touid} and isdel='0' and status=1  ";
        }

        $dynamicnums=DI()->notorm->dynamic
            ->where($whered)
            ->count();
        if(!$dynamicnums){
            $dynamicnums=0;
        }

        $info['dynamicnums']=(string)$dynamicnums;
        /* 直播数 */
        $livenums=DI()->notorm->live_record
            ->where('uid=?',$touid)
            ->count();

        $info['livenums']=$livenums;
        /* 直播记录 */
        $record=array();
        $record=DI()->notorm->live_record
            ->select("id,uid,nums,starttime,endtime,title,city")
            ->where('uid=?',$touid)
            ->order("id desc")
            ->limit(0,50)
            ->fetchAll();
        foreach($record as $k=>$v){
            $record[$k]['datestarttime']=date("Y.m.d",$v['starttime']);
            $record[$k]['dateendtime']=date("Y.m.d",$v['endtime']);
            $cha=$v['endtime']-$v['starttime'];
            $record[$k]['length']=getSeconds($cha);
        }
        $info['liverecord']=$record;
        return $info;
    }

    /* 贡献榜 */
    public function getContributeList($touid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;

        $rs=array();
        $rs=DI()->notorm->user_coinrecord
            ->select("uid,sum(totalcoin) as total")
            ->where('touid=?',$touid)
            ->group("uid")
            ->order("total desc")
            ->limit($start,$pnum)
            ->fetchAll();

        foreach($rs as $k=>$v){
            $rs[$k]['userinfo']=getUserInfo($v['uid'],1);
        }

        return $rs;
    }

    /* 设置分销 */
    public function setDistribut($uid,$code){

        $isexist=DI()->notorm->agent
            ->select("*")
            ->where('uid=?',$uid)
            ->fetchOne();
        if($isexist){
            return 1004;
        }

        //获取邀请码用户信息
        $oneinfo=DI()->notorm->agent_code
            ->select("uid")
            ->where('code=? and uid!=?',$code,$uid)
            ->fetchOne();
        if(!$oneinfo){
            return 1002;
        }

        //获取邀请码用户的邀请信息
        $agentinfo=DI()->notorm->agent
            ->select("*")
            ->where('uid=?',$oneinfo['uid'])
            ->fetchOne();
        if(!$agentinfo){
            $agentinfo=array(
                'uid'=>$oneinfo['uid'],
                'one_uid'=>0,
            );
        }
        // 判断对方是否自己下级
        if($agentinfo['one_uid']==$uid ){
            return 1003;
        }

        $data=array(
            'uid'=>$uid,
            'one_uid'=>$agentinfo['uid'],
            'addtime'=>time(),
        );
        DI()->notorm->agent->insert($data);
        return 0;
    }


    /* 印象标签 */
    public function getImpressionLabel(){

        $key="getImpressionLabel";
        $list=getcaches($key);
        if(!$list){
            $list=DI()->notorm->label
                ->select("*")
                ->order("list_order asc,id desc")
                ->fetchAll();
            if($list){
                setcaches($key,$list);
            }

        }
        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('label', 'name', $lang);
        }
        foreach ($list as $k=>$v){
            if($lang=='en'){
                $list[$k]['name']=$v['name_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $list[$k]['name']=$translate[$v['id']];
                    }
                }
            }
        }

        return $list;
    }
    /* 用户标签 */
    public function getUserLabel($uid,$touid){
        $list=DI()->notorm->label_user
            ->select("label")
            ->where('uid=? and touid=?',$uid,$touid)
            ->fetchOne();

        return $list;

    }

    /* 设置用户标签 */
    public function setUserLabel($uid,$touid,$labels){
        $nowtime=time();
        $isexist=DI()->notorm->label_user
            ->select("*")
            ->where('uid=? and touid=?',$uid,$touid)
            ->fetchOne();
        if($isexist){
            $rs=DI()->notorm->label_user
                ->where('uid=? and touid=?',$uid,$touid)
                ->update(array( 'label'=>$labels,'uptime'=>$nowtime ) );

        }else{
            $data=array(
                'uid'=>$uid,
                'touid'=>$touid,
                'label'=>$labels,
                'addtime'=>$nowtime,
                'uptime'=>$nowtime,
            );
            $rs=DI()->notorm->label_user->insert($data);
        }

        return $rs;

    }

    /* 获取我的标签 */
    public function getMyLabel($uid){
        $rs=array();
        $list=DI()->notorm->label_user
            ->select("label")
            ->where('touid=?',$uid)
            ->fetchAll();
        $label=array();
        foreach($list as $k=>$v){
            $v_a=preg_split('/,|，/',$v['label']);
            $v_a=array_filter($v_a);
            if($v_a){
                $label=array_merge($label,$v_a);
            }

        }

        if(!$label){
            return $rs;
        }


        $label_nums=array_count_values($label);

        $label_key=array_keys($label_nums);

        $labels=$this->getImpressionLabel();

        $order_nums=array();
        foreach($labels as $k=>$v){
            if(in_array($v['id'],$label_key)){
                $v['nums']=(string)$label_nums[$v['id']];
                $order_nums[]=$v['nums'];
                $rs[]=$v;
            }
        }

        array_multisort($order_nums,SORT_DESC,$rs);

        return $rs;

    }

    /* 获取关于我们列表 */
    public function getPerSetting(){
        $rs=array();

        $list=DI()->notorm->portal_post
            ->select("id,post_title")
            ->where("type='2'")
            ->order('list_order asc')
            ->fetchAll();
        foreach($list as $k=>$v){

            $rs[]=array('id'=>'0','name'=>$v['post_title'],'thumb'=>'' ,'href'=>get_upload_path("/portal/page/index?id={$v['id']}"));
        }

        return $rs;
    }

    /* 提现账号列表 */
    public function getUserAccountList($uid){

        $list=DI()->notorm->cash_account
            ->select("*")
            ->where('uid=?',$uid)
            ->order("addtime desc")
            ->fetchAll();

        return $list;
    }

    /* 账号信息 */
    public function getUserAccount($where){

        $list=DI()->notorm->cash_account
            ->select("*")
            ->where($where)
            ->order("addtime desc")
            ->fetchAll();

        return $list;
    }
    /* 设置提账号 */
    public function setUserAccount($data){

        $rs=DI()->notorm->cash_account
            ->insert($data);

        return $rs;
    }

    /* 删除提账号 */
    public function delUserAccount($data){

        $rs=DI()->notorm->cash_account
            ->where($data)
            ->delete();

        return $rs;
    }

    /* 登录奖励信息 */
    public function LoginBonus($uid){
        $rs=array(
            'bonus_switch'=>'0',
            'bonus_day'=>'0',
            'count_day'=>'0',
            'bonus_list'=>array(),
        );

        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        $configpri=getConfigPri();
        if(!$configpri['bonus_switch']){
            return $rs;
        }
        $rs['bonus_switch']=$configpri['bonus_switch'];

        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 bonus_switch:'."\r\n",FILE_APPEND);
        /* 获取登录设置 */
        $key='loginbonus';
        $list=getcaches($key);
        if(!$list){
            $list=DI()->notorm->loginbonus
                ->select("day,coin")
                ->fetchAll();
            if($list){
                setcaches($key,$list);
            }
        }

        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 list:'."\r\n",FILE_APPEND);
        $rs['bonus_list']=$list;
        $bonus_coin=array();
        foreach($list as $k=>$v){
            $bonus_coin[$v['day']]=$v['coin'];
        }

        /* 登录奖励 */
        $signinfo=DI()->notorm->user_sign
            ->select("bonus_day,bonus_time,count_day")
            ->where('uid=?',$uid)
            ->fetchOne();
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 signinfo:'."\r\n",FILE_APPEND);
        if(!$signinfo){
            $signinfo=array(
                'bonus_day'=>'0',
                'bonus_time'=>'0',
                'count_day'=>'0',
            );
        }
        $nowtime=time();
        if($nowtime - $signinfo['bonus_time'] > 60*60*24){
            $signinfo['count_day']=0;
        }
        $rs['count_day']=(string)$signinfo['count_day'];

        if($nowtime>$signinfo['bonus_time']){
            //更新
            $bonus_time=strtotime(date("Ymd",$nowtime))+60*60*24;
            $bonus_day=$signinfo['bonus_day'];
            if($bonus_day>6){
                $bonus_day=0;
            }
            $bonus_day++;
            $coin=$bonus_coin[$bonus_day];

            if($coin){
                $rs['bonus_day']=(string)$bonus_day;
            }

        }
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 rs:'."\r\n",FILE_APPEND);
        $userinfo=DI()->notorm->user
            ->select("today_score")
            ->where('id=?',$uid)
            ->fetchOne();
        $rs['today_score']=dealPrice($userinfo['today_score']);
        return $rs;
    }

    /* 获取登录奖励 */
    public function getLoginBonus($uid){
        $rs=0;
        $day=strtotime('today');
        $configpri=getConfigPri();
        if(!$configpri['bonus_switch']){
            return $rs;
        }

        /* 获取登录设置 */
        $key='loginbonus';
        $list=getcaches($key);
        if(!$list){
            $list=DI()->notorm->loginbonus
                ->select("day,coin")
                ->fetchAll();
            if($list){
                setcaches($key,$list);
            }
        }

        $bonus_coin=array();
        foreach($list as $k=>$v){
            $bonus_coin[$v['day']]=$v['coin'];
        }

        $isadd=0;
        /* 登录奖励 */
        $signinfo=DI()->notorm->user_sign
            ->select("bonus_day,bonus_time,count_day")
            ->where('uid=?',$uid)
            ->fetchOne();
        if(!$signinfo){
            $isadd=1;
            $signinfo=array(
                'bonus_day'=>'0',
                'bonus_time'=>'0',
                'count_day'=>'0',
            );
        }
        $nowtime=time();
        if($nowtime>$signinfo['bonus_time']){
            //更新
            $bonus_time=strtotime(date("Ymd",$nowtime))+60*60*24;
            $bonus_day=$signinfo['bonus_day'];
            $count_day=$signinfo['count_day'];
            if($bonus_day>6){
                $bonus_day=0;
            }
            if($nowtime - $signinfo['bonus_time'] > 60*60*24){
                $bonus_day=0;
                $count_day=0;
            }
            $bonus_day++;
            $count_day++;


            if($isadd){
                DI()->notorm->user_sign
                    ->insert(array("uid"=>$uid,"bonus_time"=>$bonus_time,"bonus_day"=>$bonus_day,"count_day"=>$count_day ));
            }else{
                DI()->notorm->user_sign
                    ->where('uid=?',$uid)
                    ->update(array("bonus_time"=>$bonus_time,"bonus_day"=>$bonus_day,"count_day"=>$count_day ));
            }

            $score=$bonus_coin[$bonus_day];

//			if($coin){
//                DI()->notorm->user
//                    ->where('id=?',$uid)
//                    ->update(array( "coin"=>new NotORM_Literal("coin + {$coin}") ));
//

            /* 记录 */
//                $insert=array("type"=>'2',"action"=>'113',"uid"=>$uid,"touid"=>$uid,"giftid"=>$bonus_day,"giftcount"=>'0',"total"=>$coin,"showid"=>'0',"addtime"=>$nowtime );
//                DI()->notorm->user_scorerecord->insert($insert);
//                releaseScore($uid,$uid,$coin,13);

//            }

            $vipinfo=DI()->notorm->vip_user
                ->select("vip_level")
                ->where('uid=?',$uid)
                ->fetchOne();

            if($vipinfo){

                //type 任务类型 1观看直播, 2观看视频, 3直播奖励, 4打赏奖励
                $type=['1'=>T('视频点赞'),'2'=>T('视频评论'),'3'=>T('关注用户'),'4'=>T('视频收藏')];

                $addScore = 0;
                // 当天时间
                $time=strtotime(date("Y-m-d 00:00:00",time()));
                foreach($type as $k=>$v) {
                    if ($k == 1) {
                        $target = $configpri['like_video_term'];
                        $reward = $configpri['like_video_coin'];
                        $img = get_upload_path('images/day/gkzb@2x.png');
                    } else if ($k == 2) {
                        $target = $configpri['comment_video_term'];
                        $reward = $configpri['comment_video_coin'];
                        $img = get_upload_path('images/day/gkjl@2x.png');
                    } else if ($k == 3) {
                        $target = $configpri['attention_user_term'];
                        $reward = $configpri['attention_user_coin'];
                        $img = get_upload_path('images/day/zbjl@2x.png');
                    } else {
                        $target = $configpri['collect_user_term'];
                        $reward = $configpri['collect_user_coin'];
                        $img = get_upload_path('images/day/dsjl@2x.png');
                    }

                    $save = [
                        'uid' => $uid,
                        'type' => $k,
                        'target' => $target,
                        'schedule' => '0',
                        'reward' => $reward,
                        'addtime' => $time,
                        'state' => '2',
                    ];

                    $where = "uid={$uid} and type={$k}";
                    //每日任务
                    $info = DI()->notorm->user_daily_tasks
                        ->where($where)
                        ->select("*")
                        ->fetchOne();

                    if (!$info) {
                        $addScore = $addScore+$reward;
                        DI()->notorm->user_daily_tasks->insert($save);
                    }else if($time!=$info['addtime']){
                        $addScore = $addScore+$reward;
                        DI()->notorm->user_daily_tasks
                            ->where('id=?',$info['id'])
                            ->update(array("addtime"=>$time,"state"=>2));
                    }else {
                        if($info['state']!=2){
                            DI()->notorm->user_daily_tasks
                                ->where('id=?',$info['id'])
                                ->update(array("state"=>2));
                            $addScore = $addScore+$reward;
                        }
                    }
                }
                $score = $addScore+$score;
            }
            $rs = $score;
            releaseScore($uid,$uid,$score,25);
            releaseAgentScore($uid,$score);
        }

        return $rs;

    }

    //检测用户是否填写了邀请码
    public function checkIsAgent($uid){
        $info=DI()->notorm->agent->where("uid=?",$uid)->fetchOne();
        if(!$info){
            return 0;
        }

        return 1;
    }

    //用户商城提现
    public function setShopCash($data){

        $nowtime=time();

        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $money=$data['money'];

        $configpri=getConfigPri();
        $balance_cash_start=$configpri['balance_cash_start'];
        $balance_cash_end=$configpri['balance_cash_end'];
        $balance_cash_max_times=$configpri['balance_cash_max_times'];

        $day=(int)date("d",$nowtime);

        if($day < $balance_cash_start || $day > $balance_cash_end){
            return 1005;
        }

        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");

        if($balance_cash_max_times){
            $count=DI()->notorm->user_balance_cashrecord
                ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                ->count();
            if($count >= $balance_cash_max_times){
                return 1006;
            }
        }


        /* 钱包信息 */
        $accountinfo=DI()->notorm->cash_account
            ->select("*")
            ->where('id=? and uid=?',$accountid,$uid)
            ->fetchOne();

        if(!$accountinfo){
            return 1007;
        }


        /* 最低额度 */
        $balance_cash_min=$configpri['balance_cash_min'];

        if($money < $balance_cash_min){
            return 1004;
        }


        $ifok=DI()->notorm->user
            ->where('id = ? and balance>=?', $uid,$money)
            ->update(array('balance' => new NotORM_Literal("balance - {$money}")) );

        if(!$ifok){
            return 1001;
        }



        $data=array(
            "uid"=>$uid,
            "money"=>$money,
            "orderno"=>$uid.'_'.$nowtime.rand(100,999),
            "status"=>0,
            "addtime"=>$nowtime,
            "type"=>$accountinfo['type'],
            "account_bank"=>$accountinfo['account_bank'],
            "account"=>$accountinfo['account'],
            "name"=>$accountinfo['name'],
        );

        $rs=DI()->notorm->user_balance_cashrecord->insert($data);
        if(!$rs){
            return 1002;
        }

        return $rs;
    }

    //获取认证信息
    public function getAuthInfo($uid){
        $info=DI()->notorm->user_auth
            ->where("uid=?",$uid)
            ->select("uid,real_name,cer_no,mobile,front_view,back_view,handset_view,status,reason")
            ->fetchOne();
        return $info;
    }

    //提交认证信息
    public function setAuthInfo($data){

        $rs=DI()->notorm->user_auth
            ->where('uid=?',$data['uid'])
            ->update($data);
        if(!$rs) {
            $rs = DI()->notorm->user_auth->insert($data);
        }
        if(!$rs){
            return 1002;
        }

        return $rs;
    }



    //获取每日任务
    public function seeDailyTasks($uid,$type){
        $configpri=getConfigPri();
        $configpub=getConfigPub();
        $name_score=$configpub['name_score'];
        $list=[];

        $time=strtotime(date("Y-m-d 00:00:00",time()));
        if($type == 'day'){
            //type 任务类型 1观看直播, 2观看视频, 3直播奖励, 4打赏奖励, 5分享邀请奖励, 6分享视频奖励, 7分享直播奖励
            $type=[
                '5'=>T('观看直播'),'6'=>T('观看视频'),'7'=>T('主播奖励'),'8'=>T('打赏奖励'),
            ];

            // 当天时间
            foreach($type as $k=>$v){
                $data=[
                    'id'=>'0',
                    'type'=>(string)$k,
                    'title'=>$v,
                    'tip_m'=>'',
                    'state'=>'0',
                ];

                if($k==5){
                    $target=$configpri['watch_live_term'];
                    $reward=$configpri['watch_live_coin'];
                    $img=get_upload_path('images/day/gkzb@2x.png');
                }else if($k==6){
                    $target=$configpri['watch_video_term'];
                    $reward=$configpri['watch_video_coin'];
                    $img=get_upload_path('images/day/gkjl@2x.png');
                }else if($k==7){
                    $target=$configpri['open_live_term'];
                    $reward=$configpri['open_live_coin'];
                    $img=get_upload_path('images/day/zbjl@2x.png');
                }else{
                    $target=$configpri['award_live_term'];
                    $reward=$configpri['award_live_coin'];
                    $img=get_upload_path('images/day/dsjl@2x.png');
                }


                $save=[
                    'uid'=>$uid,
                    'type'=>$k,
                    'target'=>$target,
                    'schedule'=>'0',
                    'reward'=>$reward,
                    'addtime'=>$time,
                    'state'=>'0',
                ];
                $data['reward']=$reward;
                $data['img']=$img;

                $where="uid={$uid} and type={$k}";
                //每日任务
                $info=DI()->notorm->user_daily_tasks
                    ->where($where)
                    ->select("*")
                    ->fetchOne();
                $schedule = '0';
                if(!$info){
                    $info=DI()->notorm->user_daily_tasks->insert($save);


                }else if($info['addtime']!=$time){
                    $save['uptime']=time(); //更新时间
                    DI()->notorm->user_daily_tasks->where("id={$info['id']}")->update($save);
                }else{
                    $target=$info['target'];
                    $reward=$info['reward'];
                    $schedule=$info['schedule'];
                    $data['state']=$info['state'];
                }

                $data['schedule']=floor($schedule);
                $data['target']=$target;
                //提示标语
                if($k==5){
                    $tip_m=T("观看直播时长达到{target}分钟",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }else if($k==6){
                    $tip_m=T("观看视频时长达到{target}分钟",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }else if($k==7){
                    $tip_m=T("每天开播满足{target}小时",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }else{
                    $tip_m=T("打赏主播和创作者超{target}钻石",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }
                $data['id']=$info['id'];
                $data['tip_m']=$tip_m;
                $list[]=$data;
            }
        }

        if($type == 'one'){
            //type 任务类型 1观看直播, 2观看视频, 3直播奖励, 4打赏奖励
            $type=['1'=>T('视频点赞'),'2'=>T('视频评论'),'3'=>T('关注用户'),'4'=>T('视频收藏')];

            // 当天时间
            foreach($type as $k=>$v){
                $data=[
                    'id'=>'0',
                    'type'=>(string)$k,
                    'title'=>$v,
                    'tip_m'=>'',
                    'state'=>'0',
                ];

                if($k==1){
                    $target=$configpri['like_video_term'];
                    $reward=$configpri['like_video_coin'];
                    $img=get_upload_path('images/day/gkzb@2x.png');
                }else if($k==2){
                    $target=$configpri['comment_video_term'];
                    $reward=$configpri['comment_video_coin'];
                    $img=get_upload_path('images/day/gkjl@2x.png');
                }else if($k==3){
                    $target=$configpri['attention_user_term'];
                    $reward=$configpri['attention_user_coin'];
                    $img=get_upload_path('images/day/zbjl@2x.png');
                }else{
                    $target=$configpri['collect_user_term'];
                    $reward=$configpri['collect_user_coin'];
                    $img=get_upload_path('images/day/dsjl@2x.png');
                }

                $save=[
                    'uid'=>$uid,
                    'type'=>$k,
                    'target'=>$target,
                    'schedule'=>'0',
                    'reward'=>$reward,
                    'addtime'=>$time,
                    'state'=>'0',
                ];
                $data['reward']=$reward;
                $data['img']=$img;

                $where="uid={$uid} and type={$k}";
                //每日任务
                $info=DI()->notorm->user_daily_tasks
                    ->where($where)
                    ->select("*")
                    ->fetchOne();

                $schedule = '0';
                if(!$info){
                    $info=DI()->notorm->user_daily_tasks->insert($save);


                }else if($info['addtime']!=$time){
                    $save['uptime']=time(); //更新时间
                    DI()->notorm->user_daily_tasks->where("id={$info['id']}")->update($save);
                }else{
                    $target=$info['target'];
                    $schedule=$info['schedule'];
                    $data['state']=$info['state'];
                }

                $data['schedule']=floor($schedule);
                $data['target']=$target;

                //提示标语
                if($k==1){
                    $tip_m=T("视频点赞达到{target}个",['target'=>$target]);
                }else if($k==2){
                    $tip_m=T("视频评论达到{target}条",['target'=>$target]);
                }else if($k==3){
                    $tip_m=T("关注用户达到{target}个",['target'=>$target]);
                }else{
                    $tip_m=T("视频收藏达到{target}个",['target'=>$target]);
                }

                $data['id']=$info['id'];
                $data['tip_m']=$tip_m;
                $list[]=$data;
            }
        }

        if($type == 'share'){
            //type 任务类型 5分享邀请奖励, 6分享视频奖励, 7分享直播奖励
            $type=['9'=>T('分享邀请奖励'),'10'=>T('分享视频奖励'),'11'=>T('分享直播奖励'),'12'=>T('下载奖励')];

            // 当天时间
            foreach($type as $k=>$v){
                $data=[
                    'id'=>'0',
                    'type'=>(string)$k,
                    'title'=>$v,
                    'tip_m'=>'',
                    'state'=>'0',
                ];

                if($k==9){
                    $target=$configpri['share_agent_term'];
                    $reward=$configpri['share_agent_coin'];
                    $img=get_upload_path('images/day/fxjl@2x.png');
                }else if($k==10){
                    $target=$configpri['share_video_term'];
                    $reward=$configpri['share_video_coin'];
                    $img=get_upload_path('images/day/fxjl@2x.png');
                }else if($k==11){
                    $target=$configpri['share_live_term'];
                    $reward=$configpri['share_live_coin'];
                    $img=get_upload_path('images/day/fxjl@2x.png');
                }else{
                    $target=$configpri['download_term'];
                    $reward=$configpri['download_coin'];
                    $img=get_upload_path('images/day/fxjl@2x.png');
                }


                $save=[
                    'uid'=>$uid,
                    'type'=>$k,
                    'target'=>$target,
                    'schedule'=>'0',
                    'reward'=>$reward,
                    'addtime'=>$time,
                    'state'=>'0',
                ];
                $data['reward']=$reward;
                $data['img']=$img;

                $where="uid={$uid} and type={$k}";
                //每日任务
                $info=DI()->notorm->user_daily_tasks
                    ->where($where)
                    ->select("*")
                    ->fetchOne();

                $schedule = '0';
                if(!$info){
                    $info=DI()->notorm->user_daily_tasks->insert($save);


                }else if($info['addtime']!=$time){
                    $save['uptime']=time(); //更新时间
                    DI()->notorm->user_daily_tasks->where("id={$info['id']}")->update($save);
                }else{
                    $target=$info['target'];
                    $schedule=$info['schedule'];
                    $data['state']=$info['state'];
                }

                $data['schedule']=floor($schedule);
                $data['target']=$target;

                //提示标语
                if($k==9){
                    $tip_m=T("每日分享{target}次可获得奖励",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }else if($k==10){
                    $tip_m=T("每日分享{target}次可获得奖励",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }else if($k==11){
                    $tip_m=T("每日分享{target}次可获得奖励",['target'=>$target,'reward'=>$reward,'name_score'=>$name_score]);
                }else{
                    $tip_m=T("下载注册可获得奖励");
                }
                $data['id']=$info['id'];
                $data['tip_m']=$tip_m;
                $list[]=$data;
            }

        }
        return $list;
    }


    public function receiveTaskReward($uid,$taskid){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $where="id={$taskid} and uid={$uid}";
        //每日任务
        $info=DI()->notorm->user_daily_tasks
            ->where($where)
            ->select("*")
            ->fetchOne();

        if(!$info){
            $rs['code']='1001';
            $rs['msg']=T('系统繁忙,请稍后操作~');
            return $rs;
        }
        if($info['state']==0){
            $rs['code']='1001';
            $rs['msg']=T('任务未达标,请继续加油~');
        }else if($info['state']==2){
            $rs['code']='1001';
            $rs['msg']=T('奖励已送达,不能重复领取!');
        }else{
            $rs['msg']=T('奖励已送放,明天继续加油哦~');


            //更新任务状态
            $issave=DI()->notorm->user_daily_tasks
                ->where("id={$info['id']}")
                ->update(['state'=>2,'uptime'=>time()]);

            if($issave){
                $score=$info['reward'];
                releaseScore($uid,$uid,$score,28);
                releaseAgentScore($uid,$score);

                //删除用户每日任务数据
                $key="seeDailyTasks_".$uid;
                delcache($key);
            }



        }

        return $rs;
    }

    /* 用户会员信息 */
    public function getUserVip($uid) {
        $info=DI()->notorm->vip_user
            ->select("id,uid,addtime,endtime")
            ->where('uid=?',$uid)
            ->fetchOne();
        if($info&&$info['endtime']>time()){
            return true;
        }
        return false;
    }

    /* 邀请好友用户数据 */
    public function getAgent($uid) {
        $info=DI()->notorm->user
            ->select("id,user_nicename,avatar,bg_img,avatar_thumb")
            ->where('id=?',$uid)
            ->fetchOne();
        if($info){
            $info['bg_img']=get_upload_path($info['bg_img']);
            $info['avatar']=get_upload_path($info['avatar']);
            $info['avatar_thumb']=get_upload_path($info['avatar_thumb']);
            $info['vip']=getUserVip($uid);
            $agent_count=DI()->notorm->agent->where('one_uid=?',$uid)->count();
            $info['agent_count']=$agent_count;
        }
        $info['vip_list'] = [
            [
                'img' => get_upload_path('images/vip/fa.png'),
                'title' => '一键签到完成任务',
                'des' => '一键签到完成任务',
            ],
            [
                'img' => get_upload_path('images/vip/c.png'),
                'title' => '享受加密直播间',
                'des' => '享受加密直播间',
            ],
            [
                'img' => get_upload_path('images/vip/wx.png'),
                'title' => '享受发布视频打赏',
                'des' => '享受发布视频打赏',
            ],
            [
                'img' => get_upload_path('images/vip/w.png'),
                'title' => '享受群聊加密建群',
                'des' => '享受群聊加密建群',
            ],
            [
                'img' => get_upload_path('images/vip/m.png'),
                'title' => '享受更多分享权益',
                'des' => '享受更多分享权益',
            ],
        ];

        return $info;
    }

    /* 举报类型 */
    public function getReportUserClassify($classifyid) {
        if($classifyid == 0){
            $list=DI()->notorm->report_user_classify
                ->select("id,name,name_en,list_order")
                ->order('list_order asc')
                ->fetchAll();
            if($list){
                $lang=GL();
                if(!in_array($lang,['zh_cn','en'])) {
                    $translate = get_language_translate('report_user_classify', 'name', $lang);
                }
                foreach ($list as $k => $v) {
                    if($lang=='en'){
                        $list[$k]['name']=$v['name_'.$lang];
                    }else{
                        if($lang!='zh_cn'){
                            if(isset($translate[$v['id']])){
                                $list[$k]['name']=$translate[$v['id']];
                            }
                        }
                    }
                }
                return $list;
            }
        }else{
            $info=DI()->notorm->report_user_classify
                ->select("id,name,category_title")
                ->where('id=?',$classifyid)
                ->fetchOne();
            if($info){
                return true;
            }
        }
        return false;
    }

    /* 举报 */
    public function report($data) {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $rs['info']= DI()->notorm->report_user->insert($data);
        return $rs;
    }

    /* 获取语言 */
    public function getLangList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $list=DI()->notorm->language->select("language_title,code_title,code")->where('status=?',0)->order("list_order asc")->fetchAll();
        foreach ($list as $k => $v){
            $list[$k]['language_title']=$v['code_title'];

        }
        if($list){
            return $list;
        }
        return $rs;
    }

    /* 获取观看记录 */
    public function getVideoView($uid,$p) {
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $sql = 'SELECT vv.id, vv.videoid, v.thumb, v.title, vv.uid '
            . 'FROM cmf_video_view AS vv INNER JOIN cmf_video AS v '
            . 'ON vv.videoid = v.id '
            . 'WHERE vv.uid = '.$uid.' '
            . 'ORDER BY vv.id DESC '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $videoViewList = $this->getORM()->queryAll($sql, []);
        if($videoViewList){
            foreach($videoViewList as $k=>$v){
                $videoViewList[$k]['thumb']=get_upload_path($v['thumb']);
                $videoViewList[$k]['user_nicename']=getUserInfo($v['uid'],1)['user_nicename'];
            }
        }

        return $videoViewList;
    }

    /* 获取充值明细 */
    public function getChangeUserList($uid,$p,$source) {
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;

        $changeUserList=DI()->notorm->charge_user
            ->select('coin,coin_give,money,addtime')
            ->where('uid=? and source=?',$uid,$source)
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($changeUserList as $k=>$v){
            $v['title']=T('充值');
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $changeUserList[$k]=$v;
        }
        return $changeUserList;
    }

    /* 获取USDT充值明细 */
    public function getChangeUserUsdtList($uid,$p) {
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $status_title = [
            0=>'未完成',
            1=>'完成',
        ];

        $changeUserList=DI()->notorm->charge_user_usdt
            ->select('usdt,usdt_give,money,addtime')
            ->where('uid=?',$uid)
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($changeUserList as $k=>$v){
            $v['title']=T('充值');
            $v['status_text']=$status_title[$v['status']];
            $v['money']=dealPrice($v['money']);
            $v['usdt']=dealPrice($v['usdt']);
            $v['usdt_give']=dealPrice($v['usdt_give']);
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $changeUserList[$k]=$v;
        }
        return $changeUserList;
    }

    /* 获取收益明细 */
    public function getEarningsList($uid,$p) {
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $action=array(
            '1'=>T('打赏收益'),
            '2'=>T('弹幕'),
            '3'=>T('分销收益'),
            '4'=>T('家族长收益'),
            '6'=>T('房间收费'),
            '7'=>T('计时收费'),
            '10'=>T('守护'),
            '11'=>T('每观看60秒视频奖励'),
            '12'=>T('LALA兑换钻石'),
            '13'=>T('LALA兑换USDT'),
            '14'=>T('分享收益'),
        );
        $type = 1;
        $earningsList=DI()->notorm->user_voterecord
            ->select('type,action,votes,addtime')
            ->where('uid=? and type=?',$uid,$type)
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($earningsList as $k=>$v){
            $v['nums']=dealPrice($v['nums']);
            $v['total']=dealPrice($v['total']);
            $v['votes']=dealPrice($v['votes']);
            $v['title']=$action[$v['action']];
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $earningsList[$k]=$v;
        }
        return $earningsList;
    }

    /* 获取提现明细 */
    public function getCashList($uid,$p) {
        if($p<1){
            $p=1;
        }
        $status=array(
            '0'=>T('审核中'),
            '1'=>T('成功'),
            '2'=>T('失败'),
        );
        $pnum=20;
        $start=($p-1)*$pnum;
        $cashList=DI()->notorm->cash_record
            ->select('money,votes,status,addtime')
            ->where('uid=? and cash_type=0',$uid)
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($cashList as $k=>$v){
            $v['title']='普通提现';
            $v['status']=$status[$v['status']];
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $cashList[$k]=$v;
        }
        return $cashList;
    }

    /* 获取USDT提现明细 */
    public function getUsdtList($uid,$p) {
        if($p<1){
            $p=1;
        }
        $status=array(
            '0'=>T('审核中'),
            '1'=>T('完成'),
            '2'=>T('失败'),
        );
        $pnum=20;
        $start=($p-1)*$pnum;
        $usdtList=DI()->notorm->usdt_record
            ->select('money,status,addtime')
            ->where('uid=?',$uid)
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($usdtList as $k=>$v){
            $v['title']='USDT';
            $v['status']=$status[$v['status']];
            $v['money']=dealPrice($v['money']);
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $usdtList[$k]=$v;
        }
        return $usdtList;
    }

    /* 删除观看记录 */
    public function delVideoView($uid,$ids) {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        DI()->notorm->video_view
            ->where("id in ({$ids}) and uid=$uid")
            ->delete();
        return $rs;
    }


    public function checkTeenager($uid){
        $rs=array('code'=>0,'msg'=>'','info'=>array());

        $info=DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->fetchOne();

        if(!$info){

            $arr=['is_setpassword'=>'0','status'=>'0'];
            $rs['info'][0]=$arr;

            return $rs;
        }

        $arr=['is_setpassword'=>'1','status'=>(string)$info['status']];
        $rs['info'][0]=$arr;

        return $rs;
    }

    public function setTeenagerPassword($uid,$password,$type){
        $info=DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->fetchOne();

        $password=md5($password);

        if($info){

            if($type==1){ //**开启青少年模式
                if($password != $info['password']){
                    return 1001;
                }
            }

            $res=DI()->notorm->user_teenager
                ->where(['uid'=>$uid])
                ->update(
                    [
                        'edittime'=>time(),
                        'status'=>1,
                        'password'=>$password
                    ]
                );

        }else{

            //**新增记录
            $res=DI()->notorm->user_teenager
                ->where(['uid'=>$uid])
                ->insert(['uid'=>$uid,'password'=>$password,'status'=>1,'addtime'=>time()]);
        }

        if(!$res){
            return 1002;
        }

        return 1;

    }

    public function updateTeenagerPassword($uid,$oldpassword,$password){

        $info=DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->fetchOne();

        if(!$info){
            return 1001;
        }

        if(md5($oldpassword) != $info['password']){
            return 1002;
        }

        $res = DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->update(
                [
                    'password'=>md5($password),
                    'edittime'=>time()
                ]
            );


        return $res;


    }

    public function closeTeenager($uid,$password){
        $info=DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->fetchOne();

        if(!$info){
            return 1001;
        }

        if(md5($password) != $info['password']){
            return 1003;
        }

        if(!$info['status']){
            return 1002;
        }

        $res=DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->update(
                [
                    'status'=>0,
                    'edittime'=>time()
                ]
            );

        return $res;
    }

    //**定时增加用户青少年模式使用时间
    public function addTeenagerTime($uid){

        $rs=array('code'=>0,'msg'=>T('更新成功'),'info'=>array());

        $info=DI()->notorm->user_teenager
            ->where(['uid'=>$uid])
            ->fetchOne();

        $msg=T('用户未开启青少年模式');

        if(!$info){
            $rs['code']=1001;
            $rs['msg']=$msg;
            return $rs;
        }

        if(!$info['status']){
            $rs['code']=1002;
            $rs['msg']=$msg;
            return $rs;
        }


        $res = $this->checkTeenagerIsOvertime($uid);

        if($res['code']!=0){
            return $res;
        }

        $now=time();

        $info = DI()->notorm->user_teenager_time
            ->where(['uid'=>$uid])
            ->fetchOne();

        if(!$info){
            DI()->notorm->user_teenager_time->insert(['uid'=>$uid,'length'=>10,'addtime'=>$now]);
        }else{

            DI()->notorm->user_teenager_time->where(['uid'=>$uid])
                ->update(
                    array(
                        'length' => new \NotORM_Literal("length + 10"),
                        'uptime'=>$now
                    )
                );
        }

        return $rs;
    }

    //**更换背景图
    public function updateBgImg($uid,$img){
        $result=DI()->notorm->user
            ->where(['id'=>$uid])
            ->update(['bg_img'=>$img]);

        if(!$result){
            return 1001;
        }
        /* 清除缓存 */
        delCache("userinfo_".$uid);

        return 1;
    }

    //**检测用户青少年模式是否可用
    public function checkTeenagerIsOvertime($uid){
        $rs=array('code'=>0,'msg'=>'','info'=>array());

        $now=time();

        $hour=date("H",$now);

        //**测试用$hour=22;

        if($hour>=22 || $hour<6){
            $rs['code']=10010; //**code固定
            $rs['msg']=T('青少年模式下每日晚22时至次日6时期间无法使用APP');
            return $rs;
        }

        $info = DI()->notorm->user_teenager_time
            ->where(['uid'=>$uid])
            ->fetchOne();

        //**测试用$info['length']=2500;

        if($info){

            if($info['length'] >= 40*60){
                $rs['code']=10011; //**code固定
                $rs['msg']=T('青少年模式下你今日的使用时长已超过40分钟，不能继续使用APP');
                return $rs;
            }
        }

        return $rs;
    }

    //**获取用户兑换页数据
    public function getConversionInfo($uid){
        $rs=array('code'=>0,'msg'=>'','info'=>array());

        $info = DI()->notorm->user
            ->select('popo,usdt,coin,votes')
            ->where(['id'=>$uid])
            ->fetchOne();
        $info['lala'] = dealPrice($info['votes']);
        $info['usdt'] = dealPrice($info['usdt']);
        $info['popo'] = dealPrice($info['popo']);
        $info['coin'] = dealPrice($info['coin']);
        $info['votes'] = dealPrice($info['votes']);
        $info['ratio'] = DI()->config->get('app.Conversion');
        return $info;
    }

    //**获取用户兑换页数据
    public function setConversion($uid,$conversion_source,$conversion_location,$number){

        $ratio= DI()->config->get('app.Conversion');
        $nowtime = time();

        $number_get = 0;

        if($conversion_source == 'popo'){
            $userinfo=DI()->notorm->user->where("id=?",$uid)->select("popo")->fetchOne();
            $number_get = $userinfo['popo'];
        }

        if($conversion_source == 'usdt'){
            $userinfo=DI()->notorm->user->where("id=?",$uid)->select("usdt")->fetchOne();
            $number_get = $userinfo['usdt'];
        }

        if($conversion_source == 'coin'){
            $userinfo=DI()->notorm->user->where("id=?",$uid)->select("coin")->fetchOne();
            $number_get = $userinfo['coin'];
        }

        if($conversion_source == 'lala'){
            $userinfo=DI()->notorm->user->where("id=?",$uid)->select("votes")->fetchOne();
            $number_get = $userinfo['votes'];
        }

        if($number>$number_get){
            return 1004;
        }


        try {
            DI()->notorm->beginTransaction('db_appapi');
            // lala->coin
            if ($conversion_source == 'lala' && $conversion_location == 'coin') {
                $data = array(
                    "uid" => $uid,
                    "type" => 0,
                    "action" => 12,
                    "fromid" => $uid,
                    "nums" => $number,
                    "total" => $number,
                    "votes" => $number,
                    "addtime" => $nowtime,
                );

                $orm = DI()->notorm->user_voterecord;
                $rs = $orm->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }
                $id = $orm->insert_id();

                $data = array(
                    "uid" => $uid,
                    "type" => 1,
                    "action" => 33,
                    "touid" => $uid,
                    "giftid" => $id,
                    "giftcount" => $number * ($ratio['lala'] / $ratio['coin']),
                    "totalcoin" => $number * ($ratio['lala'] / $ratio['coin']),
                    "addtime" => $nowtime,
                );

                $rs = DI()->notorm->user_coinrecord->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }

                DI()->notorm->user
                    ->where('id = ?', $uid)
                    ->update(array('votes' => new NotORM_Literal("votes - {$number}"), 'coin' => new NotORM_Literal("coin + {$data['totalcoin']}"), 'conversion' => new NotORM_Literal("conversion + {$data['totalcoin']}")));
            }

            // lala->usdt
            if ($conversion_source == 'lala' && $conversion_location == 'usdt') {
                $data = array(
                    "uid" => $uid,
                    "type" => 0,
                    "action" => 13,
                    "fromid" => $uid,
                    "nums" => $number,
                    "total" => $number,
                    "votes" => $number,
                    "addtime" => $nowtime,
                );

                $orm = DI()->notorm->user_voterecord;
                $rs = $orm->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }
                $id = $orm->insert_id();

                $data = array(
                    "uid" => $uid,
                    "type" => 1,
                    "action" => 5,
                    "fromid" => $uid,
                    "actionid" => $id,
                    "nums" => $number * ($ratio['lala'] / $ratio['usdt']),
                    "total" => $number * ($ratio['lala'] / $ratio['usdt']),
                    "addtime" => $nowtime,
                );

                $rs = DI()->notorm->user_usdtrecord->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }

                DI()->notorm->user
                    ->where('id = ?', $uid)
                    ->update(array('votes' => new NotORM_Literal("votes - {$number}"), 'usdt' => new NotORM_Literal("usdt + {$data['total']}")));
            }

            // popo->coin
            if ($conversion_source == 'popo' && $conversion_location == 'coin') {
                $data = array(
                    "uid" => $uid,
                    "type" => 0,
                    "action" => 1,
                    "fromid" => $uid,
                    "nums" => $number,
                    "total" => $number,
                    "addtime" => $nowtime,
                );

                $orm = DI()->notorm->user_poporecord;
                $rs = $orm->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }
                $id = $orm->insert_id();

                $data = array(
                    "uid" => $uid,
                    "type" => 1,
                    "action" => 29,
                    "touid" => $uid,
                    "giftid" => $id,
                    "giftcount" => $number * ($ratio['popo'] / $ratio['coin']),
                    "totalcoin" => $number * ($ratio['popo'] / $ratio['coin']),
                    "addtime" => $nowtime,
                );

                $rs = DI()->notorm->user_coinrecord->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }

                DI()->notorm->user
                    ->where('id = ?', $uid)
                    ->update(array('popo' => new NotORM_Literal("popo - {$number}"), 'coin' => new NotORM_Literal("coin + {$data['totalcoin']}"), 'conversion' => new NotORM_Literal("conversion + {$data['totalcoin']}")));
            }

            // popo->usdt
            if ($conversion_source == 'popo' && $conversion_location == 'usdt') {
                $data = array(
                    "uid" => $uid,
                    "type" => 0,
                    "action" => 2,
                    "fromid" => $uid,
                    "nums" => $number,
                    "total" => $number,
                    "addtime" => $nowtime,
                );

                $orm = DI()->notorm->user_poporecord;
                $rs = $orm->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }
                $id = $orm->insert_id();

                $data = array(
                    "uid" => $uid,
                    "type" => 1,
                    "action" => 3,
                    "fromid" => $uid,
                    "actionid" => $id,
                    "nums" => $number * ($ratio['popo'] / $ratio['usdt']),
                    "total" => $number * ($ratio['popo'] / $ratio['usdt']),
                    "addtime" => $nowtime,
                );

                $rs = DI()->notorm->user_usdtrecord->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }

                DI()->notorm->user
                    ->where('id = ?', $uid)
                    ->update(array('popo' => new NotORM_Literal("popo - {$number}"), 'usdt' => new NotORM_Literal("usdt + {$data['total']}")));
            }

            // usdt->coin
            if ($conversion_source == 'usdt' && $conversion_location == 'coin') {
                $data = array(
                    "uid" => $uid,
                    "type" => 0,
                    "action" => 1,
                    "fromid" => $uid,
                    "nums" => $number,
                    "total" => $number,
                    "addtime" => $nowtime,
                );

                $orm = DI()->notorm->user_usdtrecord;
                $rs = $orm->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }
                $id = $orm->insert_id();

                $data = array(
                    "uid" => $uid,
                    "type" => 1,
                    "action" => 30,
                    "touid" => $uid,
                    "giftid" => $id,
                    "giftcount" => $number * ($ratio['usdt'] / $ratio['coin']),
                    "totalcoin" => $number * ($ratio['usdt'] / $ratio['coin']),
                    "addtime" => $nowtime,
                );

                $rs = DI()->notorm->user_coinrecord->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }

                DI()->notorm->user
                    ->where('id = ?', $uid)
                    ->update(array('usdt' => new NotORM_Literal("usdt - {$number}"), 'coin' => new NotORM_Literal("coin + {$data['totalcoin']}"), 'conversion' => new NotORM_Literal("conversion + {$data['totalcoin']}")));
            }

            // usdt->popo
            if ($conversion_source == 'usdt' && $conversion_location == 'popo') {
                $data = array(
                    "uid" => $uid,
                    "type" => 0,
                    "action" => 2,
                    "fromid" => $uid,
                    "nums" => $number,
                    "total" => $number,
                    "addtime" => $nowtime,
                );

                $orm = DI()->notorm->user_usdtrecord;
                $rs = $orm->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }
                $id = $orm->insert_id();

                $data = array(
                    "uid" => $uid,
                    "type" => 1,
                    "action" => 4,
                    "fromid" => $uid,
                    "actionid" => $id,
                    "nums" => $number * ($ratio['usdt'] / $ratio['popo']),
                    "total" => $number * ($ratio['usdt'] / $ratio['popo']),
                    "addtime" => $nowtime,
                );

                $rs = DI()->notorm->user_poporecord->insert($data);
                if (!$rs) {
                    DI()->notorm->commit('db_appapi');
                    return 1002;
                }

                DI()->notorm->user
                    ->where('id = ?', $uid)
                    ->update(array('usdt' => new NotORM_Literal("usdt - {$number}"), 'popo' => new NotORM_Literal("popo + {$data['total']}")));
            }
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }

//        // coin->usdt
//        if($conversion_source == 'coin' && $conversion_location == 'usdt'){
//            $data=array(
//                "uid"=>$uid,
//                "type"=>1,
//                "action"=>32,
//                "touid"=>$uid,
//                "totalcoin"=>$number,
//                "addtime"=>$nowtime,
//            );
//
//            $orm = DI()->notorm->user_coinrecord;
//            $rs = $orm->insert($data);
//            if(!$rs){
//                return 1002;
//            }
//            $id = $orm->insert_id();
//
//            $data=array(
//                "uid"=>$uid,
//                "type"=>1,
//                "action"=>4,
//                "fromid"=>$uid,
//                "actionid"=>$id,
//                "nums"=>$number,
//                "total"=>$number*($ratio['coin']/$ratio['usdt']),
//                "addtime"=>$nowtime,
//            );
//
//            $rs=DI()->notorm->user_usdtrecord->insert($data);
//            if(!$rs){
//                return 1002;
//            }
//
//            DI()->notorm->user
//                ->where('id = ?', $uid)
//                ->update(array('coin' => new NotORM_Literal("coin - {$number}"), 'usdt' => new NotORM_Literal("usdt + {$data['total']}")));
//        }
//
//        // coin->popo
//        if($conversion_source == 'coin' && $conversion_location == 'popo'){
//            $data=array(
//                "uid"=>$uid,
//                "type"=>1,
//                "action"=>31,
//                "touid"=>$uid,
//                "totalcoin"=>$number,
//                "addtime"=>$nowtime,
//            );
//
//            $orm = DI()->notorm->user_coinrecord;
//            $rs = $orm->insert($data);
//            if(!$rs){
//                return 1002;
//            }
//            $id = $orm->insert_id();
//
//            $data=array(
//                "uid"=>$uid,
//                "type"=>1,
//                "action"=>3,
//                "fromid"=>$uid,
//                "actionid"=>$id,
//                "nums"=>$number,
//                "total"=>$number*($ratio['coin']/$ratio['popo']),
//                "addtime"=>$nowtime,
//            );
//
//            $rs=DI()->notorm->user_poporecord->insert($data);
//            if(!$rs){
//                return 1002;
//            }
//
//            DI()->notorm->user
//                ->where('id = ?', $uid)
//                ->update(array('coin' => new NotORM_Literal("coin - {$number}"), 'popo' => new NotORM_Literal("popo + {$data['total']}")));
//        }

        return $rs;
    }

    //**获取用户兑换记录
    public function getConversionList($uid,$type,$p){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $list = [];
        if($type == 'popo'){
            $title_array = [
                1=>'POPO兑换钻石',
                2=>'POPO兑换USDT',
                3=>'钻石兑换POPO',
                4=>'USDT兑换POPO',
            ];
            $where="action in ('1','2','3','4') and uid=$uid ";
            $list=DI()->notorm->user_poporecord
                ->select('*')
                ->where($where)
                ->order('addtime desc')
                ->limit($start,$pnum)
                ->fetchAll();
            foreach($list as $k=>$v){
                $v['nums']=dealPrice($v['nums']);
                $v['total']=dealPrice($v['total']);
                $v['title']=$title_array[$v['action']];
                $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
                $list[$k]=$v;
            }
        }
        if($type == 'usdt'){
            $title_array = [
                1=>'USDT兑换钻石',
                2=>'USDT兑换POPO',
                3=>'POPO兑换USDT',
                4=>'钻石兑换USDT',
                5=>'LALA兑换USDT',
            ];
            $where="action in ('1','2','3','4','5') and uid=$uid";
            $list=DI()->notorm->user_usdtrecord
                ->select('*')
                ->where($where)
                ->order('addtime desc')
                ->limit($start,$pnum)
                ->fetchAll();

            foreach($list as $k=>$v){
                $v['nums']=dealPrice($v['nums']);
                $v['total']=dealPrice($v['total']);
                $v['title']=$title_array[$v['action']];
                $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
                $list[$k]=$v;
            }
        }
        if($type == 'coin'){
            $title_array = [
                29=>'POPO兑换钻石',
                30=>'USDT兑换钻石',
                31=>'钻石兑换POPO',
                32=>'钻石兑换USDT',
                33=>'LALA兑换钻石',
            ];
            $where="action in ('29','30','31','32','33') and uid=$uid";
            $list=DI()->notorm->user_coinrecord
                ->select('*')
                ->where($where)
                ->order('addtime desc')
                ->limit($start,$pnum)
                ->fetchAll();
            foreach($list as $k=>$v){
                $v['giftcount']=dealPrice($v['giftcount']);
                $v['totalcoin']=dealPrice($v['totalcoin']);
                $v['title']=$title_array[$v['action']];
                $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
                $v['total']=$v['totalcoin'];
                $list[$k]=$v;
            }
        }
        if($type == 'lala'){
            $title_array = [
                12=>'LALA兑换钻石',
                13=>'LALA兑换USDT',
            ];
            $where="action in ('12','13') and uid=$uid";
            $list=DI()->notorm->user_voterecord
                ->select('*')
                ->where($where)
                ->order('addtime desc')
                ->limit($start,$pnum)
                ->fetchAll();
            foreach($list as $k=>$v){
                $v['nums']=dealPrice($v['nums']);
                $v['total']=dealPrice($v['total']);
                $v['votes']=dealPrice($v['votes']);
                $v['title']=$title_array[$v['action']];
                $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
                $list[$k]=$v;
            }
        }
        return $list;
    }

    //**获取打赏挖矿页基本信息数据
    public function getMineMachineInfo($uid){
        $where="id=$uid";
        $info=DI()->notorm->user
            ->where($where)
            ->select('popo_pool,popo_share,popo_accumulative')
            ->fetchOne();

        $yesterdayMidnightTimestamp = strtotime('yesterday');
        $todayMidnightTimestamp = strtotime('today');
        $info['popo_pool'] = dealPrice($info['popo_pool']);
        $info['popo_accumulative'] = dealPrice($info['popo_accumulative']);
        $info['popo_share'] = dealPrice($info['popo_share']);
        $info['yesterday_earnings'] = (float)DI()->notorm->user_popopoolrecord->where("action in (1,4,5) and uid=$uid and addtime > $yesterdayMidnightTimestamp and addtime < $todayMidnightTimestamp")->sum('total');
        $info['today_earnings'] = (float)DI()->notorm->user_popopoolrecord->where("action in (1,4,5) and uid=$uid and addtime > $todayMidnightTimestamp")->sum('total');

        $info['yesterday_earnings'] = dealPrice($info['yesterday_earnings']);
        $info['today_earnings'] = dealPrice($info['today_earnings']);
        return $info;
    }

    //**获取POPO收益数据
    public function getPopoInfo($uid){
        $where="id=$uid";
        $info=DI()->notorm->user
            ->where($where)
            ->select('popo_pool,popo')
            ->fetchOne();
        $all_popo = $info['popo']+$info['popo_pool'];
        $info['popo'] = dealPrice($info['popo']);
        $info['popo_pool'] = dealPrice($info['popo_pool']);
        $info['all_popo'] = dealPrice($all_popo);
        return $info;
    }

    //**获取矿机等级数据
    public function getMineMachineList($uid){
        $ratio= DI()->config->get('app.Conversion');
        $ratio=$ratio['coin']/$ratio['usdt'];
        $where="id=$uid";
        $info=DI()->notorm->user
            ->select('consumption')
            ->where($where)
            ->fetchOne();
        $usdt = $info['consumption'] * $ratio;
        $list=DI()->notorm->mine_machine_level
            ->select('*')
            ->order('level asc')
            ->fetchAll();
        foreach($list as $k=>$v){
            if($usdt>$v['total']){
                $usdt = $usdt - $v['total'];
                $v['reward']=$v['total'];
            }else{
                $v['reward']=$usdt;
                $usdt = 0;
            }
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取赏金分红统计数据
    public function getMyMineMachineDividend($uid){
        $ratio= DI()->config->get('app.Conversion');
        $ratio=$ratio['coin']/$ratio['usdt'];
        $where="currency='popo'";
        $list=DI()->notorm->statistics
            ->where($where)
            ->fetchAll();
        foreach($list as $k=>$v){
            $value=$v['value']+$v['updatevalue'];
            $value=dealPrice(($value*$ratio));
            $v['value']=dealPrice($value);
            $v['updatevalue']=dealPrice($v['updatevalue']);
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取矿机等级数据
    public function getMyMineMachineList($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $nowtime = time();
        $where="uid=$uid";
        $list=DI()->notorm->user_mine_machine
            ->select('*')
            ->where($where)
            ->order('level asc')
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($list as $k=>$v){
            $seconds = $nowtime-$v['starttime'];
            $days = floor($seconds / 86400);
            $seconds = $v['endtime']-$v['addtime'];
            $endday = floor($seconds / 86400);
            $v['day']=$days;
            $v['endday']=$endday;
            $v['starttime']=date('Y.m.d',$v['starttime']);
            $v['endtime']=date('Y.m.d',$v['endtime']);
            $v['addtime']=date('Y.m.d',$v['addtime']);
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取打赏列表
    public function getMyMineMachineRewardList($uid,$p){
        $ratio= DI()->config->get('app.Conversion');
        $ratio=$ratio['coin']/$ratio['usdt'];
        if($p<1){
            $p=1;
        }
        $pnum=100;
        $start=($p-1)*$pnum;
        $where="action = 1";
        $list=DI()->notorm->user_voterecord
            ->select('total')
            ->where($where)
            ->order('addtime desc')
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($list as $k=>$v){
            $v['total'] = T('新增打赏：').$v['total']*$ratio.'USDT';
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取打赏列表
    public function getMyCoinRewardList($uid,$p){
        $ratio= DI()->config->get('app.Conversion');
        $ratio=$ratio['coin']/$ratio['usdt'];
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $title_array = [
            1=>'打赏礼物',
        ];
        $where="action = 1 and uid=$uid";
        $list=DI()->notorm->user_coinrecord
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($list as $k=>$v){
            $v['giftcount']=dealPrice($v['giftcount']);
            $v['totalcoin']=dealPrice($v['totalcoin']);
            $v['title']=$title_array[$v['action']];
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $v['total']=$v['totalcoin'];
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取打赏列表
    public function getPoPoDividendList($uid,$p){
        $title_array = [
            1=>'礼物打赏',
            2=>'分红池转出',
            3=>'市场推广分红',
            4=>'手续费分红',
            5=>'矿机产出'
        ];
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $where="action in ('1','2','3','4','5') and uid=$uid";
        $list=DI()->notorm->user_popopoolrecord
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($list as $k=>$v){
            $v['nums']=dealPrice($v['nums']);
            $v['total']=dealPrice($v['total']);
            $v['title']=$title_array[$v['action']];
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取LALA收益明细
    public function getLalaList($uid,$p){
        $title_array = [
            1=>'打赏收益',
            14=>'分享收益',
        ];
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $where="action in ('1','14') and uid=$uid";
        $list=DI()->notorm->user_voterecord
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($list as $k=>$v){
            $v['nums']=dealPrice($v['nums']);
            $v['total']=dealPrice($v['total']);
            $v['votes']=dealPrice($v['votes']);
            $v['title']=$title_array[$v['action']];
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $list[$k]=$v;
        }
        return $list;
    }

    //**获取打赏列表
    public function getScoreList($uid,$p){
        //收支行为，4购买VIP,5购买坐骑,18购买靓号,21游戏获胜,22注册奖励locked_score,23推广奖励locked_score,24注册释放到流通积分score,101-112每日任务释放积分,113每日签到释放积分
        $title_array = [
            4=>'购买VIP',
            5=>'划转',
            18=>'购买靓号',
            21=>'游戏获胜',
            22=>'注册奖励',
            23=>'分享奖励',
            24=>'释放到流通积分',
            25=>'签到释放积分',
            26=>'分享加速释放',
            27=>'团队加速释放',
            28=>'任务释放积分',
            101=>'每日任务释放积分',
            102=>'每日任务释放积分',
            103=>'每日任务释放积分',
            104=>'每日任务释放积分',
            105=>'每日任务释放积分',
            106=>'每日任务释放积分',
            107=>'每日任务释放积分',
            108=>'每日任务释放积分',
            109=>'每日任务释放积分',
            110=>'每日任务释放积分',
            111=>'每日任务释放积分',
            112=>'每日任务释放积分',
            113=>'每日签到释放积分',
        ];
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;
        $where="uid=$uid";
        $list=DI()->notorm->user_scorerecord
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($list as $k=>$v){
            if($v['type']==2){
                $v['type']=0;
            }
            $v['total']=dealPrice($v['total']);
            $v['title']=$title_array[$v['action']];
            $v['addtime']=date('Y年m月d日 H:i',$v['addtime']);
            $list[$k]=$v;
        }
        return $list;
    }


    //**获取万能积分详情数据
    public function getScoreInfo($uid){
        $where="id=$uid";
        $info=DI()->notorm->user
            ->select('score,warehouse_score,blocked_score,locked_score')
            ->where($where)
            ->fetchOne();
        $info['warehouse_score'] = $info['warehouse_score']+$info['blocked_score'];
        $info['all_score'] = $info['score']+$info['warehouse_score']+$info['locked_score'];
        $info['all_score']=dealPrice($info['all_score']);
        $info['warehouse_score']=dealPrice($info['warehouse_score']);
        $info['blocked_score']=dealPrice($info['blocked_score']);
        $info['locked_score']=dealPrice($info['locked_score']);
        return $info;
    }

    //**获取万能积分收益详情数据
    public function getScoreEarningsInfo($uid){
        $where="id=$uid";
        $info=DI()->notorm->user
            ->select('score,warehouse_score,blocked_score,locked_score')
            ->where($where)
            ->fetchOne();
        $info['all_score'] = $info['score']+$info['warehouse_score']+$info['blocked_score']+$info['locked_score'];
        $yesterdayMidnightTimestamp = strtotime('yesterday');
        $todayMidnightTimestamp = strtotime('today');


        $key2="getScoreEarningsInfo_".$uid;
        $earningsInfo=getcaches($key2);
        if(!$earningsInfo){
            $earningsInfo['yesterday_earnings'] = DI()->notorm->user_scorerecord->where("action in (25,26,27) and uid=$uid and addtime > $yesterdayMidnightTimestamp and addtime < $todayMidnightTimestamp")->sum('total');
            $earningsInfo['today_earnings'] = DI()->notorm->user_scorerecord->where("action in (25,26,27) and uid=$uid and addtime > $todayMidnightTimestamp")->sum('total');
            $earningsInfo['all_earnings'] = DI()->notorm->user_scorerecord->where("action in (25,26,27) and uid=$uid")->sum('total');
            $earningsInfo['share_earnings'] = DI()->notorm->user_scorerecord->where("uid=$uid and action=23")->sum('total');
            setCaches($key2,$earningsInfo,30);
        }
        $info['score'] = dealPrice($info['score']);
        $info['all_score'] = dealPrice($info['all_score']);
        $info['warehouse_score'] = dealPrice($info['warehouse_score']);
        $info['blocked_score'] = dealPrice($info['blocked_score']);
        $info['locked_score'] = dealPrice($info['locked_score']);
        $info['yesterday_earnings'] = dealPrice($earningsInfo['yesterday_earnings']);
        $info['today_earnings'] = dealPrice($earningsInfo['today_earnings']);
        $info['all_earnings'] = dealPrice($earningsInfo['all_earnings']);
        $info['share_earnings'] = dealPrice($earningsInfo['share_earnings']);
        return $info;
    }

    //**划转
    public function setTransferPoPoDividendToCurrency($uid,$number){

        $user_info = DI()->notorm->user
            ->select('popo_pool')
            ->where('id = ?', $uid)
            ->fetchOne();

        if($user_info['popo_pool']<$number){
            return 1011;
        }

        try {
            DI()->notorm->beginTransaction('db_appapi');
            $nowtime = time();
            $data = array(
                "uid" => $uid,
                "type" => 0,
                "action" => 2,
                "fromid" => $uid,
                "nums" => $number,
                "total" => $number,
                "addtime" => $nowtime,
            );

            $orm = DI()->notorm->user_popopoolrecord;
            $rs = $orm->insert($data);
            if (!$rs) {
                DI()->notorm->rollback('db_appapi');
                return 1002;
            }
            $id = $orm->insert_id();

            $popo_total = $number * 0.8;
            $data = array(
                "uid" => $uid,
                "type" => 1,
                "action" => 5,
                "fromid" => $uid,
                "actionid" => $id,
                "nums" => $popo_total,
                "total" => $popo_total,
                "addtime" => $nowtime,
            );

            $rs = DI()->notorm->user_poporecord->insert($data);
            if (!$rs) {
                DI()->notorm->rollback('db_appapi');
                return 1002;
            }

            DI()->notorm->user
                ->where('id = ?', $uid)
                ->update(array('popo_pool' => new NotORM_Literal("popo_pool - {$number}"), 'popo' => new NotORM_Literal("popo + {$popo_total}")));

            //统计划转
            $platform_total = $number;
            $ratio_platform = 0.2;
            $platform_total = $platform_total * $ratio_platform;
            $currency = 'popo';
            $statistics_type = 'accumulative_popo_transfer';
            $insert_statistics = [
                'type' => '1',
                'action' => 2,
                'uid' => $uid,
                'fromid' => $uid,
                'actionid' => $id,
                'nums' => $number,
                'total' => $number,
                'currency' => $currency,
                'statistics_type' => $statistics_type,
                'addtime' => $nowtime,
            ];
            DI()->notorm->statistics_record->insert($insert_statistics);
            DI()->notorm->statistics
                ->where('currency = ? and statistics_type = ?', $currency, $statistics_type)
                ->update(array('value' => new NotORM_Literal("value + {$number}")));
            $currency = 'popo';
            $statistics_type = 'accumulative_popo_contribution';
            $insert_statistics = [
                'type' => '1',
                'action' => 3,
                'uid' => $uid,
                'fromid' => $uid,
                'actionid' => $id,
                'nums' => $platform_total,
                'total' => $platform_total,
                'currency' => $currency,
                'statistics_type' => $statistics_type,
                'addtime' => $nowtime,
            ];
            DI()->notorm->statistics_record->insert($insert_statistics);
            DI()->notorm->statistics
                ->where('currency = ? and statistics_type = ?', $currency, $statistics_type)
                ->update(array('value' => new NotORM_Literal("value + {$platform_total}")));

            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
        // 市场推广分红
//        $adminuid = 1;
//        $agent =DI()->notorm->agent
//            ->select('relation_chain')
//            ->where('uid = ?', $uid)
//            ->fetchOne();
//        $relation_chain = explode(',',$agent['relation_chain']);
//        $relation_chain_count = count($relation_chain);
//        $ratio_promotion = 0.02;
//        $promotion_total = $number*$ratio_promotion;
//        for ($i=0;$i<3;$i++){
//            if(isset($relation_chain[$relation_chain_count-$i-1])){
//                $promotion_uid = $relation_chain[$relation_chain_count-$i-1];
//                if($i>0){
//                    $find_vip_user =DI()->notorm->vip_user
//                        ->select('uid')
//                        ->where('uid = ?', $promotion_uid)
//                        ->fetchOne();
//                    if(!$find_vip_user){
//                        $promotion_uid = $adminuid;
//                    }
//                }
//            }else{
//                $promotion_uid = $adminuid;
//            }
//            $insert_popo_pool=[
//                "uid"=>$promotion_uid,
//                "type"=>1,
//                "action"=>3,
//                "fromid"=>$uid,
//                "nums"=>$number,
//                "total"=>$number,
//                "addtime"=>$nowtime,
//            ];
//            DI()->notorm->user_popopoolrecord->insert($insert_popo_pool);
//            DI()->notorm->user
//                ->where('id = ?', $promotion_uid)
//                ->update(array('popo_pool' => new NotORM_Literal("popo_pool + {$promotion_total}"), 'votesearnings' => new NotORM_Literal("votesearnings + {$promotion_total}")));
//        }

        return $rs;
    }
}
