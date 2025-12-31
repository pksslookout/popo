<?php

class Model_Live extends PhalApi_Model_NotORM {
	/* 创建房间 */
	public function createRoom($uid,$data) {
        
        /* 获取主播 推荐、热门 */
        $data['ishot']='0';
        $data['isrecommend']='0';
        $userinfo=DI()->notorm->user
					->select("ishot,isrecommend")
					->where('id=?',$uid)
					->fetchOne();
        if($userinfo){
            $data['ishot']=$userinfo['ishot'];
            $data['isrecommend']=$userinfo['isrecommend'];
        }
		$isexist=DI()->notorm->live
					->select("uid,isvideo,islive,stream")
					->where('uid=?',$uid)
					->fetchOne();
		if($isexist){
            /* 判断存在的记录是否为直播状态 */
            if($isexist['isvideo']==0 && $isexist['islive']==1){
                /* 若存在未关闭的直播 关闭直播 */
                $this->stopRoom($uid,$isexist['stream']);
                
                /* 加入 */
                $rs=DI()->notorm->live->insert($data);
				/*开播直播计时---用于每日任务--记录主播开播*/
				$key='open_live_daily_tasks_'.$uid;
                $enterRoom_time=time();
				setcaches($key,$enterRoom_time);
            }else{
                /* 更新 */
                $rs=DI()->notorm->live->where('uid = ?', $uid)->update($data);
            }
		}else{
			/* 加入 */
			$rs=DI()->notorm->live->insert($data);

			
			/*开播直播计时---用于每日任务--记录主播开播*/
			$key='open_live_daily_tasks_'.$uid;
            $enterRoom_time=time();
			setcaches($key,$enterRoom_time);
		}
		if(!$rs){
			return $rs;
		}
        if($data['is_popular'] == 1){
            $data = array(
                'showid'=>$data['showid'],
                'livetime'=>time(),
            );
            DI()->notorm->live_popular->where('uid = ? and status = 0', $uid)->update($data);
        }
		return 1;
	}
	
	/* 主播粉丝 */
    public function getFansIds($touid) {
        
        $list=array();
		$fansids=DI()->notorm->user_attention
					->select("uid")
                    ->where("touid='{$touid}' and status=1")
					->fetchAll();
                    
        if($fansids){
            $uids=array_column($fansids,'uid');
            
            $pushids=DI()->notorm->user_pushid
					->select("pushid")
					->where('uid',$uids)
					->fetchAll();
            $list=array_column($pushids,'pushid');
            $list=array_filter($list);
        }
        return $list;
    }	
	
	/* 修改直播状态 */
	public function changeLive($uid,$stream,$status){

		if($status==1){
            $info=DI()->notorm->live
                    ->select("*")
					->where('uid=? and stream=?',$uid,$stream)
                    ->fetchOne();
            if($info){
                DI()->notorm->live
					->where('uid=? and stream=?',$uid,$stream)
					->update(array("islive"=>1));
            }
			return $info;
		}else{
			$this->stopRoom($uid,$stream);
			return 1;
		}
	}
	
	/* 修改直播状态 */
	public function changeLiveType($uid,$stream,$data){
		return DI()->notorm->live
				->where('uid=? and stream=?',$uid,$stream)
				->update( $data );
	}
	
	/* 关播 */
	public function stopRoom($uid,$stream) {

		$info=DI()->notorm->live
				->select("uid,showid,starttime,title,province,city,stream,lng,lat,type,type_val,liveclassid")
				->where('uid=? and stream=? and islive="1"',$uid,$stream)
				->fetchOne();
        /* file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 info:'.json_encode($info)."\r\n",FILE_APPEND); */
		if($info) {
            $isdel = DI()->notorm->live
                ->where('uid=?', $uid)
                ->delete();
            if (!$isdel) {
                return 0;
            }
            $nowtime = time();
            $info['endtime'] = $nowtime;
            $info['time'] = date("Y-m-d", $info['showid']);
            $votes = DI()->notorm->user_voterecord
                ->where('uid =? and showid=?', $uid, $info['showid'])
                ->sum('total');
            $info['votes'] = 0;
            if ($votes) {
                $info['votes'] = $votes;
            }
            $nums = DI()->redis->zCard('user_' . $stream);
            DI()->redis->hDel("livelist", $uid);
            DI()->redis->del($uid . '_zombie');
            DI()->redis->del($uid . '_zombie_uid');
            DI()->redis->del('attention_' . $uid);
            DI()->redis->del('user_' . $stream);
            $info['nums'] = $nums;
            $result = DI()->notorm->live_record->insert($info);
            /* file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result['id'])."\r\n",FILE_APPEND); */

            /* 解除本场禁言 */
            $list2 = DI()->notorm->live_shut
                ->select('uid')
                ->where('liveuid=? and showid!=0', $uid)
                ->fetchAll();
            DI()->notorm->live_shut->where('liveuid=? and showid!=0', $uid)->delete();

            foreach ($list2 as $k => $v) {
                DI()->redis->hDel($uid . 'shutup', $v['uid']);
            }

            /* 游戏处理 */
//            $game = DI()->notorm->game
//                ->select("*")
//                ->where('stream=? and liveuid=? and state=?', $stream, $uid, "0")
//                ->fetchOne();
//            $total = array();
//            if ($game) {
//                $total = DI()->notorm->gamerecord
//                    ->select("uid,sum(coin_1 + coin_2 + coin_3 + coin_4 + coin_5 + coin_6) as total")
//                    ->where('gameid=?', $game['id'])
//                    ->group('uid')
//                    ->fetchAll();
//                foreach ($total as $k => $v) {
//                    DI()->notorm->user
//                        ->where('id = ?', $v['uid'])
//                        ->update(array('coin' => new NotORM_Literal("coin + {$v['total']}")));
//
//                    $insert = array("type" => '1', "action" => '20', "uid" => $v['uid'], "touid" => $v['uid'], "giftid" => $game['id'], "giftcount" => 1, "totalcoin" => $v['total'], "showid" => 0, "addtime" => $nowtime);
//                    DI()->notorm->user_coinrecord->insert($insert);
//                }
//
//                DI()->notorm->game
//                    ->where('id = ?', $game['id'])
//                    ->update(array('state' => '3', 'endtime' => time()));
//                $brandToken = $stream . "_" . $game["action"] . "_" . $game['starttime'] . "_Game";
//                DI()->redis->del($brandToken);
//            }

            $popular = DI()->notorm->live_popular
                ->select("id,price,view_people_counts,actual_view_counts,view_counts")
                ->where('uid = ? and showid = ? and status = 0', $uid, $info['showid'])
                ->fetchOne();

            if ($popular) {
                // 更新上热门信息
                $view_people_counts = explode('-', $popular['view_people_counts']);
                $view_people_counts_0 = $view_people_counts[0];
                $view_people_counts_1 = $view_people_counts[1];
                $actual_exposure_amount = 0;
                if($popular['actual_view_counts']<$view_people_counts_0){
                    $actual_exposure_amount = $popular['actual_view_counts']/$view_people_counts_0*$popular['view_counts'];
                }
                if($popular['actual_view_counts']>$view_people_counts_1){
                    $actual_exposure_amount = $popular['actual_view_counts']/$view_people_counts_1*$popular['view_counts'];
                }
                if($popular['actual_view_counts']<$view_people_counts_1&&$popular['actual_view_counts']>$view_people_counts_0){
                    $actual_exposure_amount = $popular['view_counts'];
                }
                if ($actual_exposure_amount < $popular['view_counts']) {
                    // 未达到预计曝光量 退还金额
                    $return_price = $popular['price'] * ($popular['view_counts']-$actual_exposure_amount) / $popular['view_counts'];
                    $return_price = (int)$return_price;
                    $data_popular = array(
                        'status' => 1,
                        'liveendtime' => $nowtime,
                        'return_price' => $return_price,
                    );
                    DI()->notorm->user
                        ->where('id = ?', $uid)
                        ->update(array('coin' => new NotORM_Literal("coin + {$return_price}")));
                    $insert = array(
                        "type" => 1,
                        "action" => 28,
                        "uid" => $uid,
                        "touid" => $uid,
                        "giftid" => 0,
                        "giftcount" => 1,
                        "totalcoin" => $return_price,
                        "showid" => 0,
                        "addtime" => $nowtime
                    );
                    DI()->notorm->user_coinrecord->insert($insert);
                } else {
                    $data_popular = array(
                        'liveendtime' => $nowtime,
                        'status' => 1,
                    );
                }
                DI()->notorm->live_popular
                    ->where("id = '{$popular['id']}'")
                    ->update($data_popular);
            }

			/*主播直播奖励---每日任务*/
			$key='open_live_daily_tasks_'.$uid;
			$starttime=getcaches($key);
			if($starttime){ 
				$endtime=$nowtime;  //当前时间
				$data=[
					'type'=>'7',
					'starttime'=>$starttime,
					'endtime'=>$endtime,
				];
				dailyTasks($uid,$data);
				//删除当前存入的时间
				delcache($key);
			}
            
		}
		return 1;
	}
	/* 关播信息 */
	public function stopInfo($stream){
		
		$rs=array(
			'nums'=>0,
			'length'=>0,
			'votes'=>0,
		);
		
		$stream2=explode('_',$stream);
		$liveuid=$stream2[0];
		$starttime=$stream2[1];
		$liveinfo=DI()->notorm->live_record
					->select("starttime,endtime,nums,votes")
					->where('uid=? and starttime=?',$liveuid,$starttime)
					->fetchOne();
		if($liveinfo){
            $nums=DI()->notorm->live_user_record
                ->where('liveuid=?',$liveuid)
                ->count();
            $cha=$liveinfo['endtime'] - $liveinfo['starttime'];
			$rs['length']=getSeconds($cha,1);
			$rs['nums']=$nums;
            DI()->notorm->live_user_record
                ->where('liveuid=?',$liveuid)
                ->delete();
		}
		if($liveinfo['votes']){
			$rs['votes']=dealPrice($liveinfo['votes']);
		}
		return $rs;
	}
	
	/* 直播状态 */
	public function checkLive($uid,$liveuid,$stream){
        
        /* 是否被踢出 */
        $isexist=DI()->notorm->live_kick
					->select("id")
					->where('uid=? and liveuid=?',$uid,$liveuid)
					->fetchOne();
        if($isexist){
            return 1008;
        }
        
		$islive=DI()->notorm->live
					->select("islive,type,type_val,starttime")
					->where('uid=? and stream=?',$liveuid,$stream)
					->fetchOne();
					
		if(!$islive || $islive['islive']==0){
			return 1005;
		}
		$rs['type']=$islive['type'];
		$rs['type_val']='0';
		$rs['type_msg']='';

        $model_user=new Model_User();
        $checkTeenager = $model_user->checkTeenager($uid);
        $teenager_status=$checkTeenager['info'][0]['status'];


        if($uid>0){

            $userinfo=DI()->notorm->user
                ->select("issuper")
                ->where('id=?',$uid)
                ->fetchOne();

            if($userinfo && $userinfo['issuper']==1 && !$teenager_status){  //超管身份、非青少年模式下

                if($islive['type']==6){

                    return 1007;
                }

                $rs['type']='0';
                $rs['type_val']='0';
                $rs['type_msg']='';

                return $rs;
            }
        }
		
		$userinfo=DI()->notorm->user
				->select("issuper")
				->where('id=?',$uid)
				->fetchOne();
		if($userinfo && $userinfo['issuper']==1){
            
            if($islive['type']==6){
                
                return 1007;
            }
			$rs['type']='0';
			$rs['type_val']='0';
			$rs['type_msg']='';
			
			return $rs;
		}

		$configpub=getConfigPub();
		
		if($islive['type']==1){
			$rs['type_msg']=md5($islive['type_val']);
		}else if($islive['type']==2){
			$rs['type_msg']='本房间为收费房间，需支付'.$islive['type_val'].$configpub['name_coin'];
			$rs['type_val']=$islive['type_val'];
            //打开青少年模式
            if($teenager_status==1){
                return $rs;
            }
			$isexist=DI()->notorm->user_coinrecord
						->select('id')
						->where('uid=? and touid=? and showid=? and action=6 and type=0',$uid,$liveuid,$islive['starttime'])
						->fetchOne();
			if($isexist){
				$rs['type']='0';
				$rs['type_val']='0';
				$rs['type_msg']='';
			}
		}else if($islive['type']==3){
			$rs['type_val']=$islive['type_val'];
			$rs['type_msg']=T('本房间为计时房间，每分钟需支付').$islive['type_val'].$configpub['name_coin'];
		}
		
		return $rs;
		
	}
	
	/* 用户余额 */
	public function getUserCoin($uid){
		$userinfo=DI()->notorm->user
					->select("coin")
					->where('id=?',$uid)
					->fetchOne();
		return $userinfo;
	}
	
	/* 房间扣费 */
	public function roomCharge($uid,$liveuid,$stream){
        $rs['code'] = 400;
        $rs['msg'] = T('服务暂停');
        return $rs;
		$islive=DI()->notorm->live
					->select("islive,type,type_val,starttime")
					->where('uid=? and stream=?',$liveuid,$stream)
					->fetchOne();
		if(!$islive || $islive['islive']==0){
			return 1005;
		}
		
		if($islive['type']==0 || $islive['type']==1 ){
			return 1006;
		}
				
		$total=$islive['type_val'];
		if($total<=0){
			return 1007;
		}
        try {
            DI()->notorm->beginTransaction('db_appapi');

            /* 更新用户余额 消费 */
            $ifok = DI()->notorm->user
                ->where('id = ? and coin >= ?', $uid, $total)
                ->update(array('coin' => new NotORM_Literal("coin - {$total}"), 'consumption' => new NotORM_Literal("consumption + {$total}")));
            if (!$ifok) {
                DI()->notorm->rollback('db_appapi');
                return 1008;
            }

            $action = '6';
            if ($islive['type'] == 3) {
                $action = '7';
            }

            $giftid = 0;
            $giftcount = 0;
            $showid = $islive['starttime'];
            $addtime = time();


            /* 更新直播 映票 累计映票 */
            DI()->notorm->user
                ->where('id = ?', $liveuid)
                ->update(array('votes' => new NotORM_Literal("votes + {$total}"), 'votestotal' => new NotORM_Literal("votestotal + {$total}")));

            $insert_votes = [
                'type' => '1',
                'action' => $action,
                'uid' => $liveuid,
                'fromid' => $uid,
                'actionid' => $giftid,
                'nums' => $giftcount,
                'total' => $total,
                'showid' => $showid,
                'votes' => $total,
                'addtime' => time(),
            ];
            DI()->notorm->user_voterecord->insert($insert_votes);

            /* 更新直播 映票 累计映票 */
            DI()->notorm->user_coinrecord
                ->insert(array("type" => '0', "action" => $action, "uid" => $uid, "touid" => $liveuid, "giftid" => $giftid, "giftcount" => $giftcount, "totalcoin" => $total, "showid" => $showid, "addtime" => $addtime));

            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
		$userinfo2=DI()->notorm->user
					->select('coin')
					->where('id = ?', $uid)
					->fetchOne();	
		$rs['coin']=$userinfo2['coin'];
		return $rs;
		
	}
	
	/* 判断是否僵尸粉 */
	public function isZombie($uid) {
        $userinfo=DI()->notorm->user
					->select("iszombie")
					->where("id='{$uid}'")
					->fetchOne();
		
		return $userinfo['iszombie'];				
    }
	
	/* 僵尸粉 */
    public function getZombie($stream,$where) {
		$ids= DI()->notorm->user_zombie
            ->select('uid')
            ->where("uid not in ({$where})")
			->limit(0,10)
            ->fetchAll();	

		$info=array();

		if($ids){
            foreach($ids as $k=>$v){
                
                $userinfo=getUserInfo($v['uid'],1);
                if(!$userinfo){
                    DI()->notorm->user_zombie->where("uid={$v['uid']}")->delete();
                    continue;
                }
                
                $info[]=$userinfo;

                $score='0.'.($userinfo['level']+100).'1';
				DI()->redis -> zAdd('user_'.$stream,$score,$v['uid']);
            }	
		}
		return 	$info;		
    }
	
	/* 礼物列表 */
	public function getGiftList(){

		$rs=DI()->notorm->gift
			->select("id,type,mark,giftname,giftname_en,needcoin,gifticon,sticker_id,swftime,isplatgift")
            ->where('type!=2')
			->order("list_order asc,addtime desc")
			->fetchAll();

        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('gift', 'giftname', $lang);
        }
        foreach ($rs as $k=>$v){
            if($lang=='en'){
                $rs[$k]['giftname']=$rs[$k]['giftname_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $rs[$k]['giftname']=$translate[$v['id']];
                    }
                }
            }
        }
		return $rs;
	}
	
	/* 礼物：道具列表 */
	public function getPropgiftList(){

		$rs=DI()->notorm->gift
			->select("id,type,mark,giftname,giftname_en,needcoin,gifticon,sticker_id,swftime,isplatgift")
			->where("type=2")
			->order("list_order asc,addtime desc")
			->fetchAll();

        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('gift', 'giftname', $lang);
        }
        foreach ($rs as $k=>$v){
            if($lang=='en'){
                $rs[$k]['giftname']=$rs[$k]['giftname_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $rs[$k]['giftname']=$translate[$v['id']];
                    }
                }
            }
        }
		return $rs;
	}
	/* 赠送礼物 */
	public function sendGift($uid,$liveuid,$stream,$giftid,$giftcount,$ispack) {

        /* 礼物信息 */
		$giftinfo=DI()->notorm->gift
					->select("type,mark,giftname,giftname_en,gifticon,needcoin,swftype,swf,swftime,isplatgift,sticker_id,vote_ticket")
					->where('id=?',$giftid)
					->fetchOne();
		if(!$giftinfo){
			/* 礼物信息不存在 */
			return 1002;
		}

        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('gift', 'giftname', $lang);
        }
        if($lang=='en'){
            $giftinfo['giftname']=$giftinfo['giftname_'.$lang];
        }else{
            if($lang!='zh_cn'){
                if(isset($translate[$giftinfo['id']])){
                    $giftinfo['giftname']=$translate[$giftinfo['id']];
                }
            }
        }
        
		$total= $giftinfo['needcoin']*$giftcount;
		 
		$addtime=time();
		$type='0';
		$action='1';
		
        $stream2=explode('_',$stream);
        $showid=$stream2[1];

        try {
            DI()->notorm->beginTransaction('db_appapi');
            if ($ispack == 1) {
                /* 背包礼物 */
                $ifok = DI()->notorm->backpack
                    ->where('uid=? and giftid=? and nums>=?', $uid, $giftid, $giftcount)
                    ->update(array('nums' => new NotORM_Literal("nums - {$giftcount} ")));
                if (!$ifok) {
                    /* 数量不足 */
                    DI()->notorm->commit('db_appapi');
                    return 1003;
                }
            } else {
                /* 更新用户余额 消费 */
                $ifok = DI()->notorm->user
                    ->where('id = ? and coin >=?', $uid, $total)
                    ->update(array('coin' => new NotORM_Literal("coin - {$total}"), 'consumption' => new NotORM_Literal("consumption + {$total}")));
                if (!$ifok) {
                    /* 余额不足 */
                    DI()->notorm->commit('db_appapi');
                    return 1001;
                }

                $insert = array("type" => $type, "action" => $action, "uid" => $uid, "touid" => $liveuid, "giftid" => $giftid, "giftcount" => $giftcount, "totalcoin" => $total, "showid" => $showid, "mark" => $giftinfo['mark'], "addtime" => $addtime);
                DI()->notorm->user_coinrecord->insert($insert);
            }

            // 插入视频收取礼物记录表
            $insert = array("coin" => $total, "uid" => $uid, "number" => $giftcount, "touid" => $liveuid, "showid" => $showid, "giftid" => $giftid, "addtime" => $addtime);
            DI()->notorm->live_gift->insert($insert);

            /* 幸运礼物分成 */

            /* 幸运礼物分成 */

            /* 家族分成之后的金额 */

            // 打赏主播
            $ratio = DI()->config->get('app.Conversion');
            $ratio = $ratio['coin'] / $ratio['votes'];
            $ratio_total = $total * $ratio;
            $nowtime = time();

            /* 打赏映票 作者分红*/
            $adminuid = 1;
            if ($liveuid != 0) {
                // 获取主播信息
                $userinfo = DI()->notorm->user
                    ->select('votestotal')
                    ->where('id = ?', $liveuid)
                    ->fetchOne();
                // 获取主播等级
                $level_anchor = getLevelAnchor($userinfo['votestotal']);
                $level_anchor_ratio = DI()->config->get('app.anchor_ratio');
                $ratio_anchor = $level_anchor_ratio[$level_anchor];
                $anthor_total = $ratio_total * $ratio_anchor;
                $insert_votes = [
                    'type' => '1',
                    'action' => $action,
                    'uid' => $liveuid,
                    'fromid' => $uid,
                    'actionid' => $giftid,
                    'nums' => $giftcount,
                    'total' => $total,
                    'showid' => $showid,
                    'votes' => $anthor_total,
                    'addtime' => $nowtime,
                ];
                DI()->notorm->user_voterecord->insert($insert_votes);
                DI()->notorm->user
                    ->where('id = ?', $liveuid)
                    ->update(array('votes' => new NotORM_Literal("votes + {$anthor_total}"), 'votestotal' => new NotORM_Literal("votestotal + {$total}"), 'votesearnings' => new NotORM_Literal("votesearnings + {$anthor_total}")));
                // 接收滑落映票到管理员账号
                if ($ratio_anchor < 0.6) {
                    $ratio_anchor = 0.6 - $level_anchor_ratio[$level_anchor];
                    $anthor_total = $ratio_total * $ratio_anchor;
                    $insert_votes = [
                        'type' => '1',
                        'action' => 102,
                        'uid' => $adminuid,
                        'fromid' => $uid,
                        'actionid' => $giftid,
                        'nums' => $giftcount,
                        'total' => $total,
                        'showid' => $showid,
                        'votes' => $anthor_total,
                        'addtime' => $nowtime,
                    ];
                    DI()->notorm->user_voterecord_platform->insert($insert_votes);
                }
            } else {
                // 直接60%滑落
                $ratio_anchor = 0.6;
                $anthor_total = $ratio_total * $ratio_anchor;
                $insert_votes = [
                    'type' => '1',
                    'action' => 102,
                    'uid' => $adminuid,
                    'fromid' => $uid,
                    'actionid' => $giftid,
                    'nums' => $giftcount,
                    'total' => $total,
                    'showid' => $showid,
                    'votes' => $anthor_total,
                    'addtime' => $nowtime,
                ];
                DI()->notorm->user_voterecord_platform->insert($insert_votes);
            }

            /* 更新主播热门 */
            if ($giftinfo['mark'] == 1) {
                DI()->notorm->live
                    ->where('uid = ?', $liveuid)
                    ->update(array('hotvotes' => new NotORM_Literal("hotvotes + {$total}")));
            }

            DI()->redis->zIncrBy('user_' . $stream, $total, $uid);

            /* PK处理 */
            $key1 = 'LivePK';
            $key2 = 'LivePK_gift';

            $ispk = '0';
            $pkuid1 = '0';
            $pkuid2 = '0';
            $pktotal1 = '0';
            $pktotal2 = '0';

            $pkuid = DI()->redis->hGet($key1, $liveuid);
            if ($pkuid) {
                $ispk = '1';
                DI()->redis->hIncrBy($key2, $liveuid, $total);

                $gift_uid = DI()->redis->hGet($key2, $liveuid);
                $gift_pkuid = DI()->redis->hGet($key2, $pkuid);

                $pktotal1 = $gift_uid;
                $pktotal2 = $gift_pkuid;

                $pkuid1 = $liveuid;
                $pkuid2 = $pkuid;

            }


            /* 清除缓存 */
            delCache("userinfo_" . $uid);
            delCache("userinfo_" . $liveuid);

            $votestotal = $this->getVotes($liveuid);

            $gifttoken = md5(md5($action . $uid . $liveuid . $giftid . $giftcount . $total . $showid . $addtime . rand(100, 999)));

            $swf = $giftinfo['swf'] ? get_upload_path($giftinfo['swf']) : '';


            $ifluck = 0;
            $ifup = 0;
            $ifwin = 0;
            /* 幸运礼物 */

            /* 幸运礼物中奖 */
            $isluck = '0';
            $isluckall = '0';
            $luckcoin = '0';
            $lucktimes = '0';

            /* 幸运礼物中奖 */


            /* 奖池升级 */
            $isup = '0';
            $uplevel = '0';
            $upcoin = '0';
            /* 奖池升级 */

            /* 奖池中奖 */
            $iswin = '0';
            $wincoin = '0';
            /* 奖池中奖 */


            $userinfo2 = DI()->notorm->user
                ->select('consumption,votestotal,coin')
                ->where('id = ?', $uid)
                ->fetchOne();

            // 矿机插入
            $ratio = DI()->config->get('app.Conversion');
            $ratio = $ratio['lala'] / $ratio['usdt'];
            $votestotal_q = ($userinfo2['consumption'] - $total) * $ratio;
            $votestotal_h = $userinfo2['consumption'] * $ratio;
            $mine_usdt = getLevelMineList();
            $total_sum = 0;
            foreach ($mine_usdt as $value) {
                $total_sum = $total_sum + $value['total'];
            }
            $arr_mine_total = divideNumberIntoArray($votestotal_h, $total_sum);
            $count_arr_mine_total = count($arr_mine_total);
            for ($i = 0; $i < $count_arr_mine_total; $i++) {
                $coin_up = 0;
                $votestotal_q = $votestotal_q - ($total_sum * $i);
                foreach ($mine_usdt as $value) {
                    $coin_up = $coin_up + $value['total'];
                    if ($coin_up > $votestotal_q && $coin_up <= $arr_mine_total[$i]) {
                        $insert_mine = [
                            'level' => $value['level'],
                            'title' => $value['title'],
                            'hashrate' => $value['hashrate'],
                            'avatar' => $value['avatar'],
                            'total' => $value['total'],
                            'status' => 1,
                            'starttime' => $nowtime,
                            'endtime' => $nowtime + $value['endtime'] * 24 * 60 * 60,
                            'uid' => $uid,
                            'addtime' => $nowtime,
                        ];
                        DI()->notorm->user_mine_machine->insert($insert_mine);
                    }
                }
            }

            $level = getLevel($userinfo2['consumption']);

            if ($giftinfo['type'] != 1) {
                $giftinfo['isplatgift'] = '0';
            }

            $result = array(
                "uid" => $uid,
                "giftid" => $giftid,
                "type" => $giftinfo['type'],
                "mark" => $giftinfo['mark'],
                "giftcount" => $giftcount,
                "totalcoin" => $total,
                "giftname" => $giftinfo['giftname'],
                "gifticon" => get_upload_path($giftinfo['gifticon']),
                "swftime" => $giftinfo['swftime'],
                "swftype" => $giftinfo['swftype'],
                "swf" => $swf,
                "level" => $level,
                "coin" => $userinfo2['coin'],
                "votestotal" => dealPrice($votestotal),
                "gifttoken" => $gifttoken,
                "isplatgift" => $giftinfo['isplatgift'],
                "sticker_id" => $giftinfo['sticker_id'],

                "isluck" => $isluck,
                "isluckall" => $isluckall,
                "luckcoin" => $luckcoin,
                "lucktimes" => $lucktimes,

                "isup" => $isup,
                "uplevel" => $uplevel,
                "upcoin" => $upcoin,

                "iswin" => $iswin,
                "wincoin" => $wincoin,

                "ispk" => $ispk,
                "pkuid" => $pkuid,
                "pkuid1" => $pkuid1,
                "pkuid2" => $pkuid2,
                "pktotal1" => $pktotal1,
                "pktotal2" => $pktotal2,
            );


            /*打赏礼物---每日任务---针对于用户*/
            $data = [
                'type' => '8',
                'total' => $total,
            ];
            dailyTasks($uid, $data);

            // 清理缓存
            if($level<10){
                delcache("getUserLevelNew_v2_".$uid);
            }
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
		
		
        //file_put_contents('./zhifu.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);
		return $result;
	}		
	
	/* 发送弹幕 */
	public function sendBarrage($uid,$liveuid,$stream,$giftid,$giftcount,$content) {

		$configpri=getConfigPri();
					 
		$giftinfo=array(
			"giftname"=>'弹幕',
			"gifticon"=>'',
			"needcoin"=>$configpri['barrage_fee'],
		);		
		
		$total= $giftinfo['needcoin']*$giftcount;
		if($total<0){
            return 1002;
        }

        $addtime=time();
        $action='2';

        $userinfo2 =DI()->notorm->user
            ->select('consumption,coin,votestotal')
            ->where('id = ?', $uid)
            ->fetchOne();
        $votestotal=$userinfo2['votestotal'];
        if($total>0){

            try {
                DI()->notorm->beginTransaction('db_appapi');
                $type = '0';
                // 更新用户余额 消费
                $ifok = DI()->notorm->user
                    ->where('id = ? and coin >=?', $uid, $total)
                    ->update(array('coin' => new NotORM_Literal("coin - {$total}"), 'consumption' => new NotORM_Literal("consumption + {$total}")));
                if (!$ifok) {
                    DI()->notorm->rollback('db_appapi');
                    // 余额不足
                    return 1001;
                }

                // 更新直播 魅力值 累计魅力值

                $level_anchor = getLevelAnchor($votestotal);
                $level_anchor_ratio = DI()->config->get('app.anchor_ratio');
                $ratio_anchor = $level_anchor_ratio[$level_anchor];
                $anchor_total = $total * $ratio_anchor;
                $istouid = DI()->notorm->user
                    ->where('id = ?', $liveuid)
                    ->update(array('votes' => new NotORM_Literal("votes + {$anchor_total}"), 'votestotal' => new NotORM_Literal("votestotal + {$total}"), 'votesearnings' => new NotORM_Literal("votesearnings + {$anchor_total}")));

                $stream2 = explode('_', $stream);
                $showid = $stream2[1];
                if (!$showid) {
                    $showid = 0;
                }

                $insert_votes = [
                    'type' => '1',
                    'action' => $action,
                    'uid' => $liveuid,
                    'fromid' => $uid,
                    'actionid' => $giftid,
                    'nums' => $giftcount,
                    'total' => $total,
                    'showid' => $showid,
                    'votes' => $anchor_total,
                    'addtime' => time(),
                ];
                DI()->notorm->user_voterecord->insert($insert_votes);

                // 写入记录 或更新
                $insert = array("type" => $type, "action" => $action, "uid" => $uid, "touid" => $liveuid, "giftid" => $giftid, "giftcount" => $giftcount, "totalcoin" => $total, "showid" => $showid, "addtime" => $addtime);
                $isup = DI()->notorm->user_coinrecord->insert($insert);
                DI()->notorm->commit('db_appapi');
            }catch(\Exception $e){
                DI()->notorm->rollback('db_appapi');
                return ['code'=>400,'msg'=>$e->getMessage()];
            }


        }


			 
		$level=getLevel($userinfo2['consumption']);			
		
		/* 清除缓存 */
		delCache("userinfo_".$uid); 
		delCache("userinfo_".$liveuid); 

		$barragetoken=md5(md5($action.$uid.$liveuid.$giftid.$giftcount.$total.$showid.$addtime.rand(100,999)));
		 
		$result=array("uid"=>$uid,"content"=>$content,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"giftname"=>$giftinfo['giftname'],"gifticon"=>$giftinfo['gifticon'],"level"=>$level,"coin"=>$userinfo2['coin'],"votestotal"=>dealPrice($votestotal),"barragetoken"=>$barragetoken);
		
		return $result;
	}			
	
	/* 设置/取消 管理员 */
	public function setAdmin($liveuid,$touid){
					
		$isexist=DI()->notorm->live_manager
					->select("*")
					->where('uid=? and  liveuid=?',$touid,$liveuid)
					->fetchOne();			
		if(!$isexist){
			$count =DI()->notorm->live_manager
						->where('liveuid=?',$liveuid)
						->count();	
			if($count>=5){
				return 1004;
			}		
			$rs=DI()->notorm->live_manager
					->insert(array("uid"=>$touid,"liveuid"=>$liveuid) );	
			if($rs!==false){
				return 1;
			}else{
				return 1003;
			}				
			
		}else{
			$rs=DI()->notorm->live_manager
				->where('uid=? and  liveuid=?',$touid,$liveuid)
				->delete();		
			if($rs!==false){
				return 0;
			}else{
				return 1003;
			}						
		}
	}
	
	/* 管理员列表 */
	public function getAdminList($liveuid){
		$rs=DI()->notorm->live_manager
						->select("uid")
						->where('liveuid=?',$liveuid)
						->fetchAll();	
		foreach($rs as $k=>$v){
			$rs[$k]=getUserInfo($v['uid']);
		}	

        $info['list']=$rs;
        $info['nums']=(string)count($rs);
        $info['total']='5';
		return $info;
	}
    
	/* 举报类型 */
	public function getReportClass(){
        $list = DI()->notorm->report_classify
                    ->select("*")
					->order("list_order asc")
					->fetchAll();
        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('report_classify', 'name', $lang);
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
	
	/* 举报 */
	public function setReport($uid,$touid,$content){
		return  DI()->notorm->report
				->insert(array("uid"=>$uid,"touid"=>$touid,'content'=>$content,'addtime'=>time() ) );	
	}
	
	/* 主播总映票 */
	public function getVotes($liveuid){
		$userinfo=DI()->notorm->user
					->select("votestotal")
					->where('id=?',$liveuid)
					->fetchOne();	
		return dealPrice($userinfo['votestotal'],2);
	}
    
    /* 是否禁言 */
	public function checkShut($uid,$liveuid){
        
        $isexist=DI()->notorm->live_shut
                ->where('uid=? and liveuid=? ',$uid,$liveuid)
                ->fetchOne();
        if($isexist){
            DI()->redis -> hSet($liveuid . 'shutup',$uid,1);
        }else{
            DI()->redis -> hDel($liveuid . 'shutup',$uid);
        }
		return 1;			
	}

    /* 禁言 */
	public function setShutUp($uid,$liveuid,$touid,$showid){
        
        $isexist=DI()->notorm->live_shut
                ->where('uid=? and liveuid=? ',$touid,$liveuid)
                ->fetchOne();
        if($isexist){
            if($isexist['showid']==$showid){
                return 1002;
            }
            
            
            if($isexist['showid']==0 && $showid!=0){
                return 1002;
            }
            
            $rs=DI()->notorm->live_shut->where('id=?',$isexist['id'])->update([ 'uid'=>$touid,'liveuid'=>$liveuid,'actionid'=>$uid,'showid'=>$showid,'addtime'=>time() ]);
            
        }else{
            $rs=DI()->notorm->live_shut->insert([ 'uid'=>$touid,'liveuid'=>$liveuid,'actionid'=>$uid,'showid'=>$showid,'addtime'=>time() ]);
        }
        
        
        
		return $rs;			
	}
    
    /* 踢人 */
	public function kicking($uid,$liveuid,$touid){
        
        $isexist=DI()->notorm->live_kick
                ->where('uid=? and liveuid=? ',$touid,$liveuid)
                ->fetchOne();
        if($isexist){
            return 1002;
        }
        
        $rs=DI()->notorm->live_kick->insert([ 'uid'=>$touid,'liveuid'=>$liveuid,'actionid'=>$uid,'addtime'=>time() ]);
        
        
		return $rs;
	}
    
    /* 是否禁播 */
	public function checkBan($uid){
        
        $isexist=DI()->notorm->live_ban
                ->where('liveuid=? ',$uid)
                ->fetchOne();
        if($isexist){
            return 1;
        }
		return 0;			
	}    
	
	/* 超管关闭直播间 */
	public function superStopRoom($uid,$liveuid,$type){
		
		$userinfo=DI()->notorm->user
					->select("issuper")
					->where('id=? ',$uid)
					->fetchOne();
		
		if($userinfo['issuper']==0){
			return 1001;
		}
		
		if($type==1){
			
            /* 禁播列表 */
            $isexist=DI()->notorm->live_ban->where('liveuid=? ',$liveuid)->fetchOne();
            if($isexist){
                return 1002;
            }
            DI()->notorm->live_ban->insert([ 'liveuid'=>$liveuid,'superid'=>$uid,'addtime'=>time() ]);
		}
        
        if($type==2){
            /* 关闭并禁用 */
			DI()->notorm->user->where('id=? ',$liveuid)->update(array('user_status'=>0));
        }
		
	
		$info=DI()->notorm->live
				->select("stream")
				->where('uid=? and islive="1"',$liveuid)
				->fetchOne();
		if($info){
            $this->stopRoom($liveuid,$info['stream']);
		}

		
		return 0;
		
	}
    
    /* 获取用户本场贡献 */
    public function getContribut($uid,$liveuid,$showid){
        $sum=DI()->notorm->user_coinrecord
				->where('action=1 and uid=? and touid=? and showid=? ',$uid,$liveuid,$showid)
				->sum('totalcoin');
        if(!$sum){
            $sum=0;
        }
        
        return (string)$sum;
    }

    /* 检测房间状态 */
    public function checkLiveing($uid,$stream){
        $info=DI()->notorm->live
                ->select('uid')
				->where('uid=? and stream=? ',$uid,$stream)
				->fetchOne();
        if($info){
            return '1';
        }
        
        return '0';
    }
    
    /* 获取直播信息 */
    public function getLiveInfo($liveuid){
        
        $info=DI()->notorm->live
					->select("uid,title,city,stream,pull,thumb,isvideo,type,type_val,goodnum,anyway,starttime,isshop,game_action")
					->where('uid=? and islive=1',$liveuid)
					->fetchOne();
        if($info){
            
            $info=handleLive($info);
            
        }
        
        return $info;
    }

    //直播间在售商品列表是否正在展示状态
    public function setLiveGoodsIsShow($uid,$goodsid){

    	$rs=array('status'=>'0'); //商品展示状态 0不显示 1 展示

    	//获取商品信息
    	$model_shop=new Model_Shop();
    	$where=array('uid'=>$uid,'id'=>$goodsid);
    	$goods_info=$model_shop->getGoods($where);

    	if(!$goods_info){ //非本人发布的商品
    		
    		//判断是否为该用户代售的商品
    		
    		$where1=[];
    		$where1['uid']=$uid;
    		$where1['goodsid']=$goodsid;
    		$where1['status']=1;

    		$is_sale=checkUserSalePlatformGoods($where1);

    		if(!$is_sale){
    			return 1001;
    		}

    		$sale_info=getOnsalePlatformInfo($where1);

    		if($sale_info['live_isshow']){ //在售
    			setOnsalePlatformInfo($where1,['live_isshow'=>0]);
    		}else{
    			setOnsalePlatformInfo($where1,['live_isshow'=>1,'issale'=>1]);
    			$rs['status']='1';

    			//将自己发布的商品在售状态改为0
    			DI()->notorm->shop_goods->where("uid={$uid} and status=1 and live_isshow=1")->update(array("live_isshow"=>0));

    			//将其他代售商品的在售状态改为0
    			$where2="uid={$uid} and goodsid !={$goodsid}";
    			setOnsalePlatformInfo($where2,['live_isshow'=>0]);
    		}



    		
    	}else{ //自己发布的商品

    		if($goods_info['status']!=1){
	    		return 1002;
	    	}

	    	if($goods_info['live_isshow']==1){ //取消展示
	    		$data=array(
	    			'live_isshow'=>0
	    		);

	    		$res=$model_shop->upGoods($where,$data);
	    		if(!$res){
	    			return 1003;
	    		}


	    	}else{ //设置展示

	    		
	    		$data=array(
	    			'live_isshow'=>1
	    		);

	    		$res=$model_shop->upGoods($where,$data);
	    		if(!$res){
	    			return 1004;
	    		}
	    		//将其他展示状态的商品改为非展示状态
	    		$where1="uid={$uid} and id !={$goodsid} and live_isshow=1";
	    		$data1=array(
	    			'live_isshow'=>0
	    		);

	    		$model_shop->upGoods($where1,$data1);

	    		$rs['status']='1';

	    		//将其他代售商品的在售状态改为0
    			$where2="uid={$uid} and goodsid !={$goodsid}";
    			setOnsalePlatformInfo($where2,['live_isshow'=>0]);

	    	}


    	}

    	

    	


    	return $rs;
    }

    //获取直播间在售商品中正在展示的商品
    public function getLiveShowGoods($liveuid){

    	$res=array('goodsid'=>'0','goods_name'=>'','goods_thumb'=>'','goods_price'=>'','goods_type'=>'0');

    	//判断直播间是否开启购物车
    	$isshop=DI()->notorm->live->where("uid=?",$liveuid)->fetchOne('isshop');
    	if(!$isshop){
    		return $res;
    	}

    	$where=array(
    		'uid'=>$liveuid,
    		'status'=>1,
    		'issale'=>1,
    		'live_isshow'=>1,
    	);

    	$model_shop=new Model_Shop();
    	$goods_info=$model_shop->getGoods($where);

    	if($goods_info){
    		$goods_info=handleGoods($goods_info);
    		$res['goodsid']=$goods_info['id'];
    		$res['goods_name']=$goods_info['name'];
    		$res['goods_thumb']=$goods_info['thumbs_format'][0];
    		if($goods_info['type']==1){ //外链商品
    			$res['goods_price']=$goods_info['present_price'];
    		}else{
    			$res['goods_price']=$goods_info['specs_format'][0]['price'];
    		}
    		
    		$res['goods_type']=$goods_info['type'];

    	}else{ //代售平台商品
    		$where1=array(
    			'uid'=>$liveuid,
    			'status'=>1,
    			'issale'=>1,
    			'live_isshow'=>1
    		);
    		$onsale_platfrom_goods=getOnsalePlatformInfo($where1);

    		if($onsale_platfrom_goods){
    			$where2=array(
    				'id'=>$onsale_platfrom_goods['goodsid'],
    				'status'=>1
    			);
    			$goods_info=$model_shop->getGoods($where2);

    			if($goods_info){
		    		$goods_info=handleGoods($goods_info);
		    		$res['goodsid']=$goods_info['id'];
		    		$res['goods_name']=$goods_info['name'];
		    		$res['goods_thumb']=$goods_info['thumbs_format'][0];
		    		if($goods_info['type']==1){ //外链商品
		    			$res['goods_price']=$goods_info['present_price'];
		    		}else{
		    			$res['goods_price']=$goods_info['specs_format'][0]['price'];
		    		}
		    		
		    		$res['goods_type']=$goods_info['type'];

		    	}


    		}

    	}

    	return $res;

    }
    /* 上热门 */
    public function upPopular($uid,$price){
        $popular=DI()->notorm->live_popular
            ->select("id")
            ->where("uid='{$uid}' and status=0")
            ->fetchOne();

        if($popular){
            return 1001;
        }

        $user = DI()->notorm->user
            ->select("coin,user_nicename")
            ->where('id = ?', $uid)
            ->fetchOne();
        if ($user['coin'] < $price) {
            return 1003;
        }

        $user_nicename = $user['user_nicename'];
        $addtime=time();
        // 生成上热门记录
        $insert=array(
            "uid"=>$uid,
            "user_nicename"=>$user_nicename,
            "view_counts"=>$price*10,
            "view_people_counts"=>$price*1.5.'-'.($price*1.5+112),
            "price"=>$price,
            "is_status"=>1,
            "addtime"=>$addtime
        );
        try {
            DI()->notorm->beginTransaction('db_appapi');
            $result = DI()->notorm->live_popular->insert($insert);

            DI()->notorm->user
                ->where('id = ?', $uid)
                ->update(array('coin' => new NotORM_Literal("coin - {$price}")));

            //用户消费记录
            $type = '0';
            $action = '27';
            $giftid = $result['id'];
            $giftcount = 1;
            $total = $price;
            $showid = 0;
            $addtime = $addtime;
            $insert = array("type" => $type, "action" => $action, "uid" => $uid, "touid" => $uid, "giftid" => $giftid, "giftcount" => $giftcount, "totalcoin" => $total, "showid" => $showid, "addtime" => $addtime);
            DI()->notorm->user_coinrecord->insert($insert);

            DI()->notorm->commit('db_appapi');
            return 0;
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
    }
    /* 上热门 */
    public function getPopular($uid){
        $popular=DI()->notorm->live_popular
            ->select("id,view_counts,view_people_counts,price")
            ->where("uid='{$uid}' and status=0")
            ->fetchOne();
        if(!$popular){
            $popular = [];
        }
        return $popular;
    }
    /* 上热门规则 */
    public function getPopularRule(){
        $popular=DI()->notorm->live_popular_rule
            ->select("id,coin")
            ->order("list_order asc,id desc")
            ->fetchAll();
        if(!$popular){
            $popular = [];
        }
        foreach($popular as $k=>$v){
            $popular[$k]['view_counts']=$v['coin']*10;
            $popular[$k]['view_people_counts']=$v['coin']*1.5.'-'.($v['coin']*1.5+112);
        }
        return $popular;
    }
    /* 更新上热门观众数计算 */
    public function upPopularPeople($uid, $showid){

        $popular=DI()->notorm->live_popular
            ->select("id,actual_view_counts,view_counts")
            ->where('uid = ? and showid = ? and status = 0', $uid, $showid)
            ->fetchOne();

        if($popular){
            try {
                DI()->notorm->beginTransaction('db_appapi');
                // 更新上热门信息
                if (($popular['actual_view_counts'] + 1) >= $popular['view_counts']) {
                    $data_popular = array(
                        'status' => 1,
                        'actual_view_counts' => new NotORM_Literal("actual_view_counts + 1")
                    );
                    DI()->notorm->live_popular
                        ->where("id = '{$popular['id']}'")
                        ->update($data_popular);
                } else {
                    DI()->notorm->live_popular
                        ->where("id = '{$popular['id']}'")
                        ->update(array('actual_view_counts' => new NotORM_Literal("actual_view_counts + 1")));
                }
                DI()->notorm->commit('db_appapi');
            }catch(\Exception $e){
                DI()->notorm->rollback('db_appapi');
                return ['code'=>400,'msg'=>$e->getMessage()];
            }
        }
        return 1;
    }
}
