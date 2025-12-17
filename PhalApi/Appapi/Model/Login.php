<?php

class Model_Login extends PhalApi_Model_NotORM {

	protected $fields='id,user_nicename,user_login,bg_img,avatar,avatar_thumb,sex,signature,coin,consumption,votestotal,province,city,birthday,user_status,end_bantime,login_type,last_login_time,location';

	/* 会员登录，注册 */
    public function userLoginReg($country_code,$user_login,$source,$type,$agent_code) {

        $configpri=getConfigPri();
		$user_pass=setPass('123456aa'.rand(1000,9999));

        if($type=='email'){
            $info=DI()->notorm->user
                ->select($this->fields.',user_pass')
                ->where('user_email=?',$user_login)
                ->fetchOne();
        }else{
            $info=DI()->notorm->user
                ->select($this->fields.',user_pass')
                ->where('country_code=? and mobile=?',$country_code,$user_login)
                ->fetchOne();
        }
        $nowtime = time();
		if(!$info){
            $reg_reward=$configpri['reg_reward'];

            $img_info = DI()->notorm->user_avatar
                ->select('img_url,type')
                ->where('id=?',rand(1,719))
                ->fetchOne();
            $data=array(
                'country_code'=>$country_code,
                'user_login' => generateUniqueUsername($user_login),
                'user_pass' =>$user_pass,
                'signature' =>'这家伙很懒，什么都没留下',
                'birthday' => time()-(23*24*60*60*365),
                'avatar' => 'avatar/'.$img_info['type'].'/'.$img_info['img_url'],
                'avatar_thumb' => 'avatar/'.$img_info['type'].'/'.$img_info['img_url'].'?imageMogr2/crop/200x200/gravity/center',
                'bg_img' =>'images/user/bg@2x.png',
                'create_time' => $nowtime,
                'user_status' => 1,
                "user_type"=>2,//会员
                "source"=>$source,
                "locked_score"=>$reg_reward,
            );
            if($type=='email'){
                $data['user_email']=$user_login;
                $user_login_a=explode("@",$user_login);
                $email_number=$user_login_a[0];
                $data['user_nicename']='POPO用户'.substr($email_number,-4);
            }else{
                $data['mobile']=$user_login;
                $data['user_nicename']='POPO用户'.substr($user_login,-4);
            }
            $rs=DI()->notorm->user->insert($data);
            if(!$rs){
                return 1007;
            }
            $uid=$rs['id'];
            DI()->notorm->user_information->insert(['id'=>$uid]);

            $info=DI()->notorm->user
                ->select($this->fields.',user_pass')
                ->where('id=?',$uid)
                ->fetchOne();
            if($reg_reward>0){
                $insert=array("type"=>'1',"action"=>'22',"uid"=>$uid,"touid"=>$uid,"giftid"=>0,"giftcount"=>1,"total"=>$reg_reward,"showid"=>0,"addtime"=>time() );
                DI()->notorm->user_scorerecord->insert($insert);
            }
            $code=$this->createCode();
            $code_info=array('uid'=>$uid,'code'=>$code);
            $isexist=DI()->notorm->agent_code
                ->select("*")
                ->where('uid = ?',$uid)
                ->fetchOne();
            if($isexist){
                DI()->notorm->agent_code->where('uid = ?',$uid)->update($code_info);
            }else{
                DI()->notorm->agent_code->insert($code_info);
            }

            if(!empty($agent_code)){
                $one_agent=DI()->notorm->agent_code
                    ->select("*")
                    ->where('code = ?',$agent_code)
                    ->fetchOne();

                if($one_agent) {
                    $agent=DI()->notorm->agent
                        ->select("*")
                        ->where('uid = ?',$one_agent['uid'])
                        ->fetchOne();
                    if($agent){
                        $relation_chain = $agent['relation_chain'].','.$agent['uid'];
                    }else{
                        $relation_chain = $one_agent['uid'];
                    }

                    $data=array(
                        'uid'=>$uid,
                        'one_uid'=>$one_agent['uid'],
                        'relation_chain'=>$relation_chain,
                        'addtime'=>$nowtime,
                    );
                    DI()->notorm->agent->insert($data);

                    // 团队人数+1
                    addTeamCount($relation_chain);
                    addAgentCount($one_agent['uid']);

                    // 推广成功奖励万能积分
                    $agent_level=DI()->config->get('app.Score');
                    $relation_chain_arr = explode(',',$relation_chain);
                    $relation_chain_count = count($relation_chain_arr);
                    $isok = 0;
                    for ($i=0;$i<3;$i++){
                        if(isset($relation_chain_arr[$relation_chain_count-$i-1])){
                            $promotion_uid = $relation_chain_arr[$relation_chain_count-$i-1];
                            if($i>0){
                                $find_vip_user =DI()->notorm->vip_user
                                    ->select('uid')
                                    ->where('uid = ?', $promotion_uid)
                                    ->fetchOne();
                                if(!$find_vip_user){
                                    $isok = 1;
                                }
                            }
                            if($isok==0){
                                $insert=array("type"=>'1',"action"=>'23',"uid"=>$promotion_uid,"touid"=>$promotion_uid,"giftid"=>0,"giftcount"=>1,"total"=>$agent_level[$i],"showid"=>0,"addtime"=>time() );
                                DI()->notorm->user_scorerecord->insert($insert);
                                DI()->notorm->user
                                    ->where('id = ?', $promotion_uid)
                                    ->update(array('locked_score' => new NotORM_Literal("locked_score + {$agent_level[$i]}")));
                            }
                        }
                    }

                    // 计算上级的邀请数量是否等于5 是则开通会员
                    $endtime = 0;
                    $one_agent_count=DI()->notorm->agent->where('one_uid = ?',$one_agent['uid'])->count();
                    if($one_agent_count >= 5){ // 考虑并发 待优化
                        $find_vip=DI()->notorm->vip_user->where('uid = ?',$one_agent['uid'])->fetchOne();
                        if(!$find_vip){
                            delcache('vip_'.$one_agent['uid']);
                            DI()->notorm->vip_user->insert(['endtime' => $endtime, 'uid' => $one_agent['uid'], 'addtime' => $nowtime]);
                            // 团队vip人数+1
                            addTeamVipCount($relation_chain);
                        }
                    }

                    // 注册奖励积分进行团队加速释放
                    for ($i=0;$i<$relation_chain_count;$i++){
                        if(isset($relation_chain_arr[$relation_chain_count-$i-1])){
                            $promotion_uid = $relation_chain_arr[$relation_chain_count-$i-1];
                            // 查询上级团队等级
                            $find_team_user =DI()->notorm->user
                                ->select('id,team_level,team_count,agent_count')
                                ->where('id = ?', $promotion_uid)
                                ->fetchOne();
                            // 检索团队积分等级是否需要增加
                            $team_count = $find_team_user['team_count']-1;
                            $agent_count = $find_team_user['agent_count'];
                            $level = 0;
                            if($team_count>=20&&$agent_count>=5){
                                $level = 1;
                            }
                            if($team_count>=80&&$agent_count>=10){
                                $level = 2;
                            }
                            if($team_count>=500&&$agent_count>=15){
                                $level = 3;
                            }
                            if($team_count>=2000&&$agent_count>=25){
                                $level = 4;
                            }
                            if($team_count>=10000&&$agent_count>=35){
                                $level = 5;
                            }
                            if($level>0&&$level!=$find_team_user['team_level']&&$find_team_user['team_update_status']!=1){
                                if($level==1){
                                    DI()->notorm->user->where('id = ?',$promotion_uid)->update(['team_level'=>1]);
                                }else{
                                    DI()->notorm->user->where('id = ?',$promotion_uid)->update(['team_update_status'=>1]);
                                }
                            }
//                    if($find_team_user['team_level']>0){
//                        $total_team = $reg_reward / 100;
//                        // 释放万能积分
//                        releaseScoreTeam($uid,$promotion_uid,$total_team,24);
//                    }
                        }
                    }

                    // 邀请10个会员赠送矿机
                    if($one_agent_count >= 10){ // 考虑并发 待优化
                        $mine_usdt = getLevelMineList();
                        $find_mine=DI()->notorm->user_mine_machine->where('uid = ? and source = 1',$one_agent['uid'])->fetchOne();
                        if(!$find_mine) {
                            $insert_mine = [
                                'level' => $mine_usdt[0]['level'],
                                'title' => $mine_usdt[0]['title'],
                                'hashrate' => $mine_usdt[0]['hashrate'],
                                'avatar' => $mine_usdt[0]['avatar'],
                                'total' => $mine_usdt[0]['total'],
                                'status' => 1,
                                'starttime' => $nowtime,
                                'endtime' => $nowtime + $mine_usdt[0]['endtime'] * 24 * 60 * 60,
                                'uid' => $one_agent['uid'],
                                'source' => 1,
                                'addtime' => $nowtime,
                            ];
                            DI()->notorm->user_mine_machine->insert($insert_mine);
                        }
                    }
                }
            }
		}
		unset($info['user_pass']);

        
        if($info['user_status']=='0'){
			return 1003;					
		}

		if($info['end_bantime']>time()){
			return 1002;					
		}

		if($info['user_status']=='3'){
			return 1004;
		}
        $uid=$info['id'];

		unset($info['user_status']);

		unset($info['end_bantime']);
		
		$info['isreg']='0';
		
        
		if($info['last_login_time']==0){
			$info['isreg']='1';
		}
		
        
        if($info['birthday']){
            $info['birthday']=date('Y-m-d',$info['birthday']);   
        }else{
            $info['birthday']='';
        }
        
		$info['level']=getLevel($info['consumption']);
		$info['level_anchor']=getLevelAnchor($info['votestotal']);

		$token=md5(md5($info['id'].$user_login.time()));
		
		$info['token']=$token;
		$info['bg_img']=get_upload_path($info['bg_img']);
		$info['avatar']=get_upload_path($info['avatar']);
		$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);

        $usersign=txImUserSign($uid);
        $info['usersign']=$usersign;

		$this->updateToken($info['id'],$token);

        $info['id']=(string)$info['id'];
        $info['sex']=(string)$info['sex'];
        $info['coin']=(string)$info['coin'];
        $info['consumption']=(string)$info['consumption'];
        $info['votestotal']=dealPrice($info['votestotal']);

        // 检测下载app的任务是否完成
//        if(in_array($source,['android','ios'])){
//            checkOneTask($uid,105,1);
//        }
		
        return $info;
    }
	/* 会员登录 */
    public function userLogin($country_code,$user_login,$user_pass,$type) {

		$user_pass=setPass($user_pass);

        if($type=='email'){
            $info=DI()->notorm->user
                ->select($this->fields.',user_pass')
                ->where('user_email=?',$user_login)
                ->fetchOne();
        }else{
            $info=DI()->notorm->user
                ->select($this->fields.',user_pass')
                ->where('country_code=? and mobile=?',$country_code,$user_login)
                ->fetchOne();
        }
		if(!$info || $info['user_pass'] != $user_pass){
			return 1001;
		}
		unset($info['user_pass']);

        if($info['user_status']=='0'){
			return 1003;
		}

		if($info['end_bantime']>time()){
			return 1002;
		}

		if($info['user_status']=='3'){
			return 1004;
		}
        $uid=$info['id'];

		unset($info['user_status']);

		unset($info['end_bantime']);

		$info['isreg']='0';


		if($info['last_login_time']==0){
			$info['isreg']='1';
		}


        if($info['birthday']){
            $info['birthday']=date('Y-m-d',$info['birthday']);
        }else{
            $info['birthday']='';
        }

		$info['level']=getLevel($info['consumption']);
		$info['level_anchor']=getLevelAnchor($info['votestotal']);

		$token=md5(md5($info['id'].$user_login.time()));

		$info['token']=$token;
		$info['avatar']=get_upload_path($info['avatar']);
		$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);

        $usersign=txImUserSign($info['id']);
        $info['usersign']=$usersign;

		$this->updateToken($info['id'],$token);

        $info['id']=(string)$info['id'];
        $info['sex']=(string)$info['sex'];
        $info['coin']=(string)$info['coin'];
        $info['consumption']=(string)$info['consumption'];
        $info['votestotal']=(string)$info['votestotal'];

        return $info;
    }
	
	public function getUserban($user_login){
		$userinfo=DI()->notorm->user
				->select('id,end_bantime')
				->where('user_login=?',$user_login)
				->fetchOne();
		 return  $this->baninfo($userinfo['id'],$userinfo['end_bantime']);
	
	}

	public function baninfo($uid,$end_bantime){
		$rs=array("ban_long"=>0,"ban_lon1g"=>0,"ban_reason"=>"","end_bantime"=>0,"ban_tip"=>'');
		$baninfo=DI()->notorm->user_banrecord
				->select('*')
				->where('uid=? ',$uid) 
				->fetchOne();
		if($baninfo){
			$rs['ban_long']=getBanSeconds($baninfo['ban_long']-time());
			$rs['ban_lon1g']=$baninfo['ban_long'];
			$rs['ban_reason']=$baninfo['ban_reason'];
			$rs['end_bantime']=date("Y-m-d",$end_bantime);
			$rs['ban_tip']="本次封禁时间为".$rs['ban_long']."，账号将于".$rs['end_bantime']."解除封禁。";
		}		
		return $rs;
	}

	public function getThirdUserban($openid,$type){
		
		$userinfo=DI()->notorm->user
				->select('id,end_bantime')
				  ->where('openid=? and login_type=? ',$openid,$type)
				->fetchOne();
				
		$rs=$this->baninfo($userinfo['id'],$userinfo['end_bantime']);
		return $rs;
	}

	/* 会员注册 */
    public function userReg($country_code,$user_login,$user_pass,$source,$type,$agent_code) {
        $nowtime =time();
		$user_pass=setPass($user_pass);
		
		$configpri=getConfigPri();
		$reg_reward=$configpri['reg_reward'];

        $img_info = DI()->notorm->user_avatar
            ->select('img_url,type')
            ->where('id=?',rand(1,719))
            ->fetchOne();
		$data=array(
            'country_code'=>$country_code,
            'user_login' => generateUniqueUsername($user_login),
			'user_pass' =>$user_pass,
			'signature' =>'这家伙很懒，什么都没留下',
            'birthday' => time()-(23*24*60*60*365),
			'avatar' => 'avatar/'.$img_info['type'].'/'.$img_info['img_url'],
			'avatar_thumb' => 'avatar/'.$img_info['type'].'/'.$img_info['img_url'].'?imageMogr2/crop/200x200/gravity/center',
            'last_login_ip' =>$_SERVER['REMOTE_ADDR'],
			'create_time' => time(),
            'bg_img' =>'images/user/bg@2x.png',
			'user_status' => 1,
			"user_type"=>2,//会员
			"source"=>$source,
            "locked_score"=>$reg_reward,
		);

        if($type=='email'){
            $data['user_email']=$user_login;
            $user_login_a=explode("@",$user_login);
            $email_number=$user_login_a[0];
            $data['user_nicename']='POPO用户'.substr($email_number,-4);
        }else{
            $data['mobile']=$user_login;
            $data['user_nicename']='POPO用户'.substr($user_login,-4);
        }
        if($type=='email'){
            $data['user_email']=$user_login;
            $isexist=DI()->notorm->user
                ->select('id')
                ->where('user_email=?',$user_login)
                ->fetchOne();
        }else{
            $data['mobile']=$user_login;
            $isexist=DI()->notorm->user
                ->select('id')
                ->where('country_code=? and mobile=?',$country_code,$user_login)
                ->fetchOne();
        }

		if($isexist){
			return 1006;
		}

		$rs=DI()->notorm->user->insert($data);
		if(!$rs){
			return 1007;
		}
        $uid=$rs['id'];
        DI()->notorm->user_information->insert(['id'=>$uid]);

        if($reg_reward>0){
            $insert=array("type"=>'1',"action"=>'22',"uid"=>$uid,"touid"=>$uid,"giftid"=>0,"giftcount"=>1,"total"=>$reg_reward,"showid"=>0,"addtime"=>time() );
            DI()->notorm->user_scorerecord->insert($insert);
        }
        $code=$this->createCode();
        $code_info=array('uid'=>$uid,'code'=>$code);
        $isexist=DI()->notorm->agent_code
            ->select("*")
            ->where('uid = ?',$uid)
            ->fetchOne();
        if($isexist){
            DI()->notorm->agent_code->where('uid = ?',$uid)->update($code_info);
        }else{
            DI()->notorm->agent_code->insert($code_info);
        }

        if(!empty($agent_code)){
            $one_agent=DI()->notorm->agent_code
                ->select("*")
                ->where('code = ?',$agent_code)
                ->fetchOne();

            if($one_agent) {
                $agent=DI()->notorm->agent
                    ->select("*")
                    ->where('uid = ?',$one_agent['uid'])
                    ->fetchOne();
                if($agent){
                    $relation_chain = $agent['relation_chain'].','.$agent['uid'];
                }else{
                    $relation_chain = $one_agent['uid'];
                }

                $data=array(
                    'uid'=>$uid,
                    'one_uid'=>$one_agent['uid'],
                    'relation_chain'=>$relation_chain,
                    'addtime'=>$nowtime,
                );
                DI()->notorm->agent->insert($data);

                // 团队人数+1
                addTeamCount($relation_chain);
                addAgentCount($one_agent['uid']);

                // 推广成功奖励万能积分
                $agent_level=DI()->config->get('app.Score');
                $relation_chain_arr = explode(',',$relation_chain);
                $relation_chain_count = count($relation_chain_arr);
                $isok = 0;
                for ($i=0;$i<3;$i++){
                    if(isset($relation_chain_arr[$relation_chain_count-$i-1])){
                        $promotion_uid = $relation_chain_arr[$relation_chain_count-$i-1];
                        if($i>0){
                            $find_vip_user =DI()->notorm->vip_user
                                ->select('uid')
                                ->where('uid = ?', $promotion_uid)
                                ->fetchOne();
                            if(!$find_vip_user){
                                $isok = 1;
                            }
                        }
                        if($isok==0){
                            $insert=array("type"=>'1',"action"=>'23',"uid"=>$promotion_uid,"touid"=>$promotion_uid,"giftid"=>0,"giftcount"=>1,"total"=>$agent_level[$i],"showid"=>0,"addtime"=>time() );
                            DI()->notorm->user_scorerecord->insert($insert);
                            DI()->notorm->user
                                ->where('id = ?', $promotion_uid)
                                ->update(array('locked_score' => new NotORM_Literal("locked_score + {$agent_level[$i]}")));
                        }
                    }
                }

                // 计算上级的邀请数量是否等于5 是则开通会员
                $endtime = 0;
                $one_agent_count=DI()->notorm->agent->where('one_uid = ?',$one_agent['uid'])->count();
                if($one_agent_count >= 5){ // 考虑并发 待优化
                    $find_vip=DI()->notorm->vip_user->where('uid = ?',$one_agent['uid'])->fetchOne();
                    if(!$find_vip){
                        delcache('vip_'.$one_agent['uid']);
                        DI()->notorm->vip_user->insert(['endtime' => $endtime, 'uid' => $one_agent['uid'], 'addtime' => $nowtime]);
                        // 团队vip人数+1
                        addTeamVipCount($relation_chain);
                    }
                }

                // 注册奖励积分进行团队加速释放
                for ($i=0;$i<$relation_chain_count;$i++){
                    if(isset($relation_chain_arr[$relation_chain_count-$i-1])){
                        $promotion_uid = $relation_chain_arr[$relation_chain_count-$i-1];
                        // 查询上级团队等级
                        $find_team_user =DI()->notorm->user
                            ->select('id,team_level,team_count,agent_count')
                            ->where('id = ?', $promotion_uid)
                            ->fetchOne();
                        // 检索团队积分等级是否需要增加
                        $team_count = $find_team_user['team_count']-1;
                        $agent_count = $find_team_user['agent_count'];
                        $level = 0;
                        if($team_count>=20&&$agent_count>=5){
                            $level = 1;
                        }
                        if($team_count>=80&&$agent_count>=10){
                            $level = 2;
                        }
                        if($team_count>=500&&$agent_count>=15){
                            $level = 3;
                        }
                        if($team_count>=2000&&$agent_count>=25){
                            $level = 4;
                        }
                        if($team_count>=10000&&$agent_count>=35){
                            $level = 5;
                        }
                        if($level>0&&$level!=$find_team_user['team_level']&&$find_team_user['team_update_status']!=1){
                            if($level==1){
                                DI()->notorm->user->where('id = ?',$promotion_uid)->update(['team_level'=>1]);
                            }else{
                                DI()->notorm->user->where('id = ?',$promotion_uid)->update(['team_update_status'=>1]);
                            }
                        }
//                    if($find_team_user['team_level']>0){
//                        $total_team = $reg_reward / 100;
//                        // 释放万能积分
//                        releaseScoreTeam($uid,$promotion_uid,$total_team,24);
//                    }
                    }
                }

                // 邀请10个会员赠送矿机
                if($one_agent_count >= 10){ // 考虑并发 待优化
                    $mine_usdt = getLevelMineList();
                    $find_mine=DI()->notorm->user_mine_machine->where('uid = ? and source = 1',$one_agent['uid'])->fetchOne();
                    if(!$find_mine) {
                        $insert_mine = [
                            'level' => $mine_usdt[0]['level'],
                            'title' => $mine_usdt[0]['title'],
                            'hashrate' => $mine_usdt[0]['hashrate'],
                            'avatar' => $mine_usdt[0]['avatar'],
                            'total' => $mine_usdt[0]['total'] * 1000,
                            'status' => 1,
                            'starttime' => $nowtime,
                            'endtime' => $nowtime + $mine_usdt[0]['endtime'] * 24 * 60 * 60,
                            'uid' => $one_agent['uid'],
                            'source' => 1,
                            'addtime' => $nowtime,
                        ];
                        DI()->notorm->user_mine_machine->insert($insert_mine);
                    }
                }
            }

        }
		return 1;
    }	

	/* 找回密码 */
	public function userFindPass($country_code,$user_login,$user_pass,$type){
        if($type=='email'){
            $isexist=DI()->notorm->user
                ->select('id')
                ->where('user_email=?',$user_login)
                ->fetchOne();
        }else{
            $isexist=DI()->notorm->user
                ->select('id')
                ->where('country_code=? and mobile=?',$country_code,$user_login)
                ->fetchOne();
        }
		if(!$isexist){
			return 1006;
		}		
		$user_pass=setPass($user_pass);

		return DI()->notorm->user
				->where('id=?',$isexist['id']) 
				->update(array('user_pass'=>$user_pass));
		
	}	

	/* 更新token 登陆信息 */
    public function updateToken($uid,$token,$data=array()) {
        $nowtime=time();
		$expiretime=$nowtime+60*60*24*300;

        try {
            DI()->notorm->beginTransaction('db_appapi');
            DI()->notorm->user
                ->where('id=?', $uid)
                ->update(array('last_login_time' => $nowtime, "last_login_ip" => $_SERVER['REMOTE_ADDR']));

            $isok = DI()->notorm->user_token
                ->where('user_id=?', $uid)
                ->update(array("token" => $token, "expire_time" => $expiretime, 'create_time' => $nowtime));
            if (!$isok) {
                DI()->notorm->user_token
                    ->insert(array("user_id" => $uid, "token" => $token, "expire_time" => $expiretime, 'create_time' => $nowtime,));
            }
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
        }

		$token_info=array(
			'uid'=>$uid,
			'token'=>$token,
			'expire_time'=>$expiretime,
		);
		
		setcaches("token_".$uid,$token_info);		
        
		return 1;
    }	
	
	/* 生成邀请码 */
	public function createCode($len=6,$format='ALL2'){
        $is_abc = $is_numer = 0;
        $password = $tmp =''; 
        switch($format){
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'ALL2':
                $chars='ABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
                break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars='0123456789';
                break;
            default :
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        
        while(strlen($password)<$len){
            $tmp =substr($chars,(mt_rand()%strlen($chars)),1);
            if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
                $is_numer = 1;
            }
            if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
                $is_abc = 1;
            }
            $password.= $tmp;
        }
        if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
            $password = $this->createCode($len,$format);
        }
        if($password!=''){
            
            $oneinfo=DI()->notorm->agent_code
	            ->select("uid")
	            ->where("code=?",$password)
	            ->fetchOne();
	        
            if(!$oneinfo){
                return $password;
            }            
        }
        $password = $this->createCode($len,$format);
        return $password;
    }
    
    /* 更新极光ID */
    public function upUserPush($uid,$pushid){
        
        $isexist=DI()->notorm->user_pushid
                    ->select('*')
                    ->where('uid=?',$uid)
                    ->fetchOne();
        if(!$isexist){
            DI()->notorm->user_pushid->insert(array('uid'=>$uid,'pushid'=>$pushid));
        }else if($isexist['pushid']!=$pushid){
            DI()->notorm->user_pushid->where('uid=?',$uid)->update(array('pushid'=>$pushid));
        }
        return 1;
    }

    //获取注销账号条件
    public function getCancelCondition($uid){

    	$res=array('list'=>array(),'can_cancel'=>'0');

    	$list=array(
    		'0'=>array(
    				'title'=>T('1、账号内无大额未消费或未提现的财产'),
    				'content'=>T('你账号内无未结清的欠款、资金和虚拟权益，无正在处理的提现记录；注销后，账户中的虚拟权益等将作废无法恢复。'),
    				'is_ok'=>'0'
    			),
    		'1'=>array(
    				'title'=>T('2、账号无其它正在进行中的业务及争议纠纷'),
    				'content'=>T('本账号内已无其它正在进行中的经营性业务、未完成的交易、无任何未处理完成的纠纷（比如退款申请、退款中、待收货等）'),
    				'is_ok'=>'0'
    			)
    	);

    	//获取用户的映票、钻石、余额
    	$userinfo=DI()->notorm->user->where("id=?",$uid)->select("coin,votes,balance")->fetchOne();

    	//获取用户映票提现未处理记录
    	$votes_cashlist=DI()->notorm->cash_record->where("uid=? and status=0",$uid)->fetchAll();
    	//获取余额提现记录未处理记录
    	$balance_cashlist=DI()->notorm->user_balance_cashrecord->where("uid=? and status=0",$uid)->fetchAll();
    	
    	//钻石小于100，映票小于100，余额为0
    	if($userinfo['coin']<100 && $userinfo['votes']<100 && $userinfo['balance']==0 && !$votes_cashlist && !$balance_cashlist){
    		$list[0]['is_ok']='1';
    	}

    	//获取用户作为买家的交易记录
    	$buyer_orderlist=DI()->notorm->shop_order->where("uid=? and (status=0 or status=1 or status=2 or (status=5 and refund_status=0))",$uid)->select("id,status")->fetchAll(); //订单待付款、待发货、待收货、待退款、退款

    	//获取用户作为卖家的交易记录
    	$seller_orderlist=DI()->notorm->shop_order->where("shop_uid=? and (status=0 or status=1 or status=2 or (status=5 and refund_status=0))",$uid)->select("id,status")->fetchAll();


    	if(!$buyer_orderlist && !$seller_orderlist){
    		$list[1]['is_ok']='1';
    	}

    	if($list[0]['is_ok']==1&&$list[1]['is_ok']==1){
    		$res['can_cancel']='1';
    	}

    	$res['list']=$list;

    	return $res;

    }

    //注销账号
    public function cancelAccount($uid){

    	

    	$condition=$this->getCancelCondition($uid);

    	if(!$condition['can_cancel']){
    		return 1001;
    	}

    	
    	$now=time();

        try {
            DI()->notorm->beginTransaction('db_appapi');
            //审核通过的商品全部下架
            DI()->notorm->shop_goods->where("uid=? and status=1", $uid)->update(array('status' => -2, 'uptime' => $now));
            //付费内容下架
            DI()->notorm->paidprogram->where("uid=?", $uid)->update(array('status' => -1));
            //修改用户昵称
            DI()->notorm->user->where("id=?", $uid)->update(array('user_nicename' => '用户已注销', 'user_status' => 3));
            //未审核的视频改为拒绝
            DI()->notorm->video->where("uid=? and status=0", $uid)->update(array('status' => 2));
            //上架的视频改为下架
            DI()->notorm->video->where("uid=? and status=1 and isdel=0", $uid)->update(array('isdel' => 1));
            //未审核的动态拒绝
            DI()->notorm->dynamic->where("uid=? and status=0", $uid)->update(array('status' => 2));
            //已经审核通过的动态下架
            DI()->notorm->dynamic->where("uid=? and status=1 and isdel=0", $uid)->update(array('isdel' => 1));
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
        delcache("userinfo_".$uid);
		return 1;

    }
}
