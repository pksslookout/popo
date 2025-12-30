<?php

class Model_Video extends PhalApi_Model_NotORM {
	/* 发布视频 */
	public function setVideo($data,$music_id) {
		$uid = $data['uid'];
        $now = time();

		$configPri=getConfigPri();

		if($configPri['is_video_audit']==0){
			$data['status']=1;
		}

        //非VIP用户每天可上传视频数
		if($configPri['vip_switch']==1){
            $userVip = getUserVip($uid);
            if($userVip['type']==0){
                $todayMidnight = strtotime("today midnight", $now);
                $videoCount=DI()->notorm->video->where("uid=? and addtime > $todayMidnight",$uid)->count();
                if($videoCount >= $configPri['not_vip_user_video_number']){
                    return 1020;
                }
            }
		}

		if($configPri['is_auth']==1){
            $isauth=isAuth($data['uid']);
            if(!$isauth){
                return 1008;
            }
		}
		//视频分类是否存在
		if($data['classid']){
			$isexitclass=DI()->notorm->video_class->where("id=?",$data['classid'])->fetchOne();
			if(!$isexitclass){
				return 1007;//视频分类不存在
            }
		}
		//话题标签是否存在
		if($data['dynamic_label_id']){
			$isexitdynamiclabel=DI()->notorm->dynamic_label->where("id=?",$data['dynamic_label_id'])->fetchOne();
			if(!$isexitdynamiclabel){
				return 1009;//话题标签不存在
            }
		}

		$result= DI()->notorm->video->insert($data);

		if($music_id>0){ //更新背景音乐被使用次数
			DI()->notorm->music
            ->where("id = '{$music_id}'")
		 	->update( array('use_nums' => new NotORM_Literal("use_nums + 1") ) );
		}
		
		return $result;
	}	

	/* 评论/回复 */
    public function setComment($data) {
    	$videoid=$data['videoid'];
        $orm = DI()->notorm->video_comments;
        $orm->insert($data);
        $commentid = $orm->insert_id();

        if(!$commentid){
            return 1001;
        }

		/* 更新 视频 */
		DI()->notorm->video
            ->where("id = '{$videoid}'")
		 	->update( array('comments' => new NotORM_Literal("comments + 1") ) );

        $dataTask=[
            'type'=>'2',
            'nums'=>'1',
        ];
        dailyTasks($data['uid'],$dataTask);

        if(!empty($data['at_info'])){
            $at_info = json_decode($data['at_info'], true);
            $data_at_info = array();
            if(!empty($at_info)){
                foreach($at_info as $k=>$v){
                    $data_at_info[$k] = [
                        'uid' => $data['uid'],
                        'videoid' => $data['videoid'],
                        'addtime' => $data['addtime'],
                        'touid' => $v['uid'],
                        'commentid' => $commentid,
                    ];
                }
                DI()->notorm->video_comments_at
                    ->insert_multi($data_at_info);
            }
        }

		$videoinfo=DI()->notorm->video
					->select("comments")
					->where('id=?',$videoid)
					->fetchOne();

        $count = 0;
        if(!empty($data['commentid'])){
            $count=DI()->notorm->video_comments
                ->where("commentid='{$data['commentid']}'")
                ->count();
        }
		$rs=array(
			'comments'=>$videoinfo['comments'],
			'replys'=>$count,
		);
        /* 插入@用户列表 */

		return $rs;
    }

    /* 赠送礼物 */
    public function sendGift($uid,$videoid,$giftid,$giftcount,$ispack) {

        $nowtime = time();
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
                if(isset($translate[$giftid])){
                    $giftinfo['giftname']=$translate[$giftid];
                }
            }
        }
        /* 礼物信息 */
        $videoinfo=DI()->notorm->video
            ->select("uid")
            ->where('id=?',$videoid)
            ->fetchOne();
        if(!$videoinfo){
            return 1004;
        }
        $videouid = $videoinfo['uid'];


        $total= $giftinfo['needcoin']*$giftcount;

        $addtime=time();
        $type='0';
        $action='1';
        $showid=0;

        try {
            DI()->notorm->beginTransaction('db_appapi');
            if ($ispack == 1) {
                /* 背包礼物 */
                $ifok = DI()->notorm->backpack
                    ->where('uid=? and giftid=? and nums>=?', $uid, $giftid, $giftcount)
                    ->update(array('nums' => new NotORM_Literal("nums - {$giftcount} ")));
                if (!$ifok) {
                    /* 数量不足 */
                    DI()->notorm->rollback('db_appapi');
                    return 1003;
                }
            } else {
                /* 更新用户余额 消费 */
                $ifok = DI()->notorm->user
                    ->where('id = ? and coin >=?', $uid, $total)
                    ->update(array('coin' => new NotORM_Literal("coin - {$total}"), 'consumption' => new NotORM_Literal("consumption + {$total}")));
                if (!$ifok) {
                    /* 余额不足 */
                    DI()->notorm->rollback('db_appapi');
                    return 1001;
                }

                $insert = array("type" => $type, "action" => $action, "uid" => $uid, "touid" => $videouid, "giftid" => $giftid, "giftcount" => $giftcount, "totalcoin" => $total, "showid" => $showid, "mark" => $giftinfo['mark'], "addtime" => $addtime);
                DI()->notorm->user_coinrecord->insert($insert);
            }


            // 插入视频收取礼物记录表
            $insert = array("coin" => $total, "uid" => $uid, "number" => $giftcount, "touid" => $videouid, "videoid" => $videoid, "giftid" => $giftid, "addtime" => $addtime);
            DI()->notorm->video_gift->insert($insert);

            /* 幸运礼物分成 */

            /* 幸运礼物分成 */

            /* 家族分成之后的金额 */

            // 打赏主播
            $ratio = DI()->config->get('app.Conversion');
            $ratio = $ratio['coin'] / $ratio['votes'];
            $ratio_total = $total * $ratio;

            /* 打赏映票 作者分红*/
            $adminuid = 1;
            if ($videouid != 0) {
                $userinfo = DI()->notorm->user
                    ->select('votestotal')
                    ->where('id = ?', $videouid)
                    ->fetchOne();
                // 获取主播等级
                $level_anchor = getLevelAnchor($userinfo['votestotal']);
                $level_anchor_ratio = DI()->config->get('app.anchor_ratio');
                $ratio_anchor = $level_anchor_ratio[$level_anchor];
                $anthor_total = $ratio_total * $ratio_anchor;
                $insert_votes = [
                    'type' => '1',
                    'action' => $action,
                    'uid' => $videouid,
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
                    ->where('id = ?', $videouid)
                    ->update(array('votes' => new NotORM_Literal("votes + {$anthor_total}"), 'votestotal' => new NotORM_Literal("votestotal + {$total}"), 'votesearnings' => new NotORM_Literal("votesearnings + {$anthor_total}"), 'votescreateearnings' => new NotORM_Literal("votescreateearnings + {$anthor_total}")));
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

            /* 清除缓存 */
            delCache("userinfo_" . $uid);

            if ($videouid != 0) {
                delCache("userinfo_" . $videouid);
                $votestotal = $this->getVotes($videouid);
            } else {
                $votestotal = 0;
            }

            $gifttoken = md5(md5($action . $uid . $videouid . $giftid . $giftcount . $total . $showid . $addtime . rand(100, 999)));

            $swf = $giftinfo['swf'] ? get_upload_path($giftinfo['swf']) : '';

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
                "votestotal" => $votestotal,
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


    /* 主播总映票 */
    public function getVotes($liveuid){
        $userinfo=DI()->notorm->user
            ->select("votestotal")
            ->where('id=?',$liveuid)
            ->fetchOne();
        return $userinfo['votestotal'];
    }

    /*收藏/取消收藏视频*/
    public function collectVideo($uid,$videoid){

        //判断是否存在
        $info=DI()->notorm->video->select("title,addtime")->where("id=?",$videoid)->fetchOne();


        if(!$info){
            return 1001;
        }

        //判断用户是否收藏过该视频
        $isexist=DI()->notorm->video_collection->select("*")->where("uid='{$uid}' and videoid='{$videoid}'")->fetchOne();


        //已经收藏过
        if($isexist){

            if($isexist['status']==1){ //已收藏

                DI()->notorm->video
                    ->where("id = '{$videoid}'")
                    ->update( array('collections' => new NotORM_Literal("collections - 1") ) );
                //将状态改为取消收藏
                $result=DI()->notorm->video_collection->where("uid=? and videoid=?",$uid,$videoid)->update(array("status"=>0,"updatetime"=>time()));
                $dataTask=[
                    'type'=>'4',
                    'nums'=>'1',
                ];
                dailyTasks($uid,$dataTask);
                if($result!==false){
                    return 200;
                }else{
                    return 201;
                }
            }else{ //改为收藏

                //将状态改为收藏

                DI()->notorm->video
                    ->where("id = '{$videoid}'")
                    ->update( array('collections' => new NotORM_Literal("collections + 1") ) );
                $result=DI()->notorm->video_collection->where("uid=? and videoid=?",$uid,$videoid)->update(array("status"=>1,"updatetime"=>time()));
                $dataTask=[
                    'type'=>'4',
                    'nums'=>'1',
                ];
                dailyTasks($uid,$dataTask);
                if($result!==false){
                    return 300;
                }else{
                    return 301;
                }
            }

        }else{

            DI()->notorm->video
                ->where("id = '{$videoid}'")
                ->update( array('collections' => new NotORM_Literal("collections + 1") ) );
            //向收藏表中写入记录
            $data=array("uid"=>$uid,"videoid"=>$videoid,'addtime'=>time(),'status'=>1);
            $result=DI()->notorm->video_collection->insert($data);
            $dataTask=[
                'type'=>'4',
                'nums'=>'1',
            ];
            dailyTasks($uid,$dataTask);
            if($result!==false){
                return 300;
            }else{
                return 301;
            }
        }

    }
	/* 阅读 */
	public function addView($uid,$videoid){
		$view=DI()->notorm->video_view
				->select("id")
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->fetchOne();

		if(!$view){
			DI()->notorm->video_view
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
						
//			DI()->notorm->video
//				->where("id = '{$videoid}'")
//				->update( array('view' => new NotORM_Literal("view + 1") ) );
		}

		/*//用户看过的视频存入redis中
		$readLists=DI()->redis -> Get('readvideo_'.$uid);
		$readArr=array();
		if($readLists){
			$readArr=json_decode($readLists,true);
			if(!in_array($videoid,$readArr)){
				$readArr[]=$videoid;
			}
		}else{
			$readArr[]=$videoid;
		}

		DI()->redis -> Set('readvideo_'.$uid,json_encode($readArr));*/

		DI()->notorm->video
				->where("id = '{$videoid}'")
				->update( array('views' => new NotORM_Literal("views + 1") ) );

        $popular=DI()->notorm->popular
            ->select("id,actual_view_counts,view_counts")
            ->where("videoid='{$videoid}' and status=0")
            ->fetchOne();

        if($popular){
            // 更新上热门信息
            if(($popular['actual_view_counts']+1) >= $popular['view_counts']){
                $data_popular = array(
                    'status' => 1,
                    'actual_view_counts' => new NotORM_Literal("actual_view_counts + 1")
                );
                DI()->notorm->popular
                    ->where("id = '{$popular['id']}'")
                    ->update($data_popular);
            }else{
                DI()->notorm->popular
                    ->where("id = '{$popular['id']}'")
                    ->update( array('actual_view_counts' => new NotORM_Literal("actual_view_counts + 1") ) );
            }
        }
		return 0;
	}
	/* 上热门 */
	public function upPopular($uid,$videoid,$price,$duration){
		$popular=DI()->notorm->popular
				->select("id")
				->where("videoid='{$videoid}' and status=0")
				->fetchOne();

		if($popular){
            return 1001;
		}
		$video=DI()->notorm->video
				->select("uid")
				->where("id='{$videoid}'")
				->fetchOne();
		if(!$video){
            return 1002;
		}
        $user_nicename = getUserInfo($uid,1)['user_nicename'];
        $release_user_nicename = getUserInfo($video['uid'],1)['user_nicename'];
        $configPri=getConfigPri();
        $addtime=time();
        // 生成上热门记录
        $insert=array(
            "uid"=>$uid,
            "user_nicename"=>$user_nicename,
            "release_uid"=>$video['uid'],
            "release_user_nicename"=>$release_user_nicename,
            "videoid"=>$videoid,
            "price"=>$price,
            "duration"=>$duration,
            "view_counts"=>$price*$duration*$configPri['popular_base_number'],
            "is_status"=>1,
            "addtime"=>$addtime
        );
        $result = DI()->notorm->popular->insert($insert);

        DI()->notorm->user
            ->where('id = ?', $uid)
            ->update(array('coin' => new NotORM_Literal("coin - {$price}")));

        //用户消费记录
        $type='0';
        $action='25';
        $giftid=$result['id'];
        $giftcount=1;
        $total=$price;
        $showid=0;
        $addtime=$addtime;
        $insert=array("type"=>$type,"action"=>$action,"uid"=>$uid,"touid"=>$video['uid'],"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime );
        DI()->notorm->user_coinrecord->insert($insert);

		return 0;
	}
	/* 点赞 */
	public function addLike($uid,$videoid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$video=DI()->notorm->video
				->select("likes,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();

		if(!$video){
			return 1001;
		}
		if($video['uid']==$uid){
			return 1002;//不能给自己点赞
		}
		$like=DI()->notorm->video_like
						->select("uid")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->video_like
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();
			
			DI()->notorm->video
				->where("id = '{$videoid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';
            $dataTask=[
                'type'=>'1',
                'nums'=>'1',
            ];
            dailyTasks($uid,$dataTask);
		}else{
			DI()->notorm->video_like
						->insert(array("uid"=>$uid,"touid"=>$video['uid'],"videoid"=>$videoid,"addtime"=>time() ));
			
			DI()->notorm->video
				->where("id = '{$videoid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
            $dataTask=[
                'type'=>'1',
                'nums'=>'1',
            ];
            dailyTasks($uid,$dataTask);
		}	
		
		$video=DI()->notorm->video
				->select("likes,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();
				
		$rs['likes']=$video['likes'];
		
		return $rs;
	}
	/* 设置不感兴趣 */
	public function setUnconcern($uid,$videoid){

        DI()->redis->select(1);
        $key = 'unconcern_'.$uid;
        // 向列表添加元素
        $where = [];
        $readLists=DI()->redis -> Get($key);
        if($readLists){
            $where=json_decode($readLists,true);
            if(count($where)>100){
                $list = DI()->notorm->video_unconcern
                    ->where("uid = '{$uid}'")
                    ->order("addtime desc")
                    ->limit(0,50)
                    ->fetchAll();
                foreach($list as $k=>$v){
                    $where[] = $v['videoid'];
                }
            }
        }
        $where1[] = $videoid;

        $unconcern=DI()->notorm->video_unconcern
            ->select("uid")
            ->where("uid='{$uid}' and videoid='{$videoid}'")
            ->fetchOne();
        if(!$unconcern){
            DI()->notorm->video_unconcern
                ->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time()));
        }
        //将两数组合并
        $where2=array_merge($where,$where1);
        $result = array_unique($where2);
        DI()->redis -> set($key,json_encode($result));
        return 1;
    }
	/* 推荐 */
	public function addRecommend($uid,$videoid){
		$rs=array(
			'isrecommend'=>'0',
			'recommend'=>'0',
		);
		$video=DI()->notorm->video
				->select("recommends,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();

		if(!$video){
			return 1001;
		}
		if($video['uid']==$uid){
			return 1002;//不能给自己点赞
		}
        $recommend=DI()->notorm->video_recommend
						->select("uid")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($recommend){
			DI()->notorm->video_recommend
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();

			DI()->notorm->video
				->where("id = '{$videoid}'")
				->update( array('recommends' => new NotORM_Literal("recommends - 1") ) );
			$rs['isrecommends']='0';
		}else{
			DI()->notorm->video_recommend
						->insert(array("uid"=>$uid,"touid"=>$video['uid'],"videoid"=>$videoid,"addtime"=>time() ));

		}

		$video=DI()->notorm->video
				->select("recommends,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();

		$rs['recommend']=$video['recommends'];

		return $rs;
	}

	/* 踩 */
	public function addStep($uid,$videoid){
		$rs=array(
			'isstep'=>'0',
			'steps'=>'0',
		);
		$like=DI()->notorm->video_step
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->video_step
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();
			
			DI()->notorm->video
				->where("id = '{$videoid}' and steps>0")
				->update( array('steps' => new NotORM_Literal("steps - 1") ) );
			$rs['isstep']='0';
		}else{
			DI()->notorm->video_step
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
			
			DI()->notorm->video
				->where("id = '{$videoid}'")
				->update( array('steps' => new NotORM_Literal("steps + 1") ) );
			$rs['isstep']='1';
		}	
		
		$video=DI()->notorm->video
				->select("steps")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['steps']=$video['steps'];
		return $rs; 		
	}

	/* 分享 */
	public function addShare($uid,$videoid){

		
		$rs=array(
			'isshare'=>'0',
			'shares'=>'0',
		);
		DI()->notorm->video
			->where("id = '{$videoid}'")
			->update( array('shares' => new NotORM_Literal("shares + 1") ) );
		$rs['isshare']='1';

		
		$video=DI()->notorm->video
				->select("shares")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['shares']=$video['shares'];
		
		return $rs; 		
	}

	/* 拉黑视频 */
	public function setBlack($uid,$videoid){
		$rs=array(
			'isblack'=>'0',
		);
		$like=DI()->notorm->video_black
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->video_black
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();
			$rs['isshare']='0';
		}else{
			DI()->notorm->video_black
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
			$rs['isshare']='1';
		}	
		return $rs; 		
	}


	/* 评论/回复 点赞 */
	public function addCommentLike($uid,$commentid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);

		//根据commentid获取对应的评论信息
		$commentinfo=DI()->notorm->video_comments
			->where("id='{$commentid}'")
			->fetchOne();

		if(!$commentinfo){
			return 1001;
		}
		if($commentinfo['uid']==$uid){
			return 1002;
		}

		$like=DI()->notorm->video_comments_like
			->select("id")
			->where("uid='{$uid}' and commentid='{$commentid}'")
			->fetchOne();

		if($like){
			DI()->notorm->video_comments_like
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->delete();
			
			DI()->notorm->video_comments
				->where("id = '{$commentid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';

		}else{
			DI()->notorm->video_comments_like
						->insert(array("uid"=>$uid,"commentid"=>$commentid,"addtime"=>time(),"touid"=>$commentinfo['uid'],"videoid"=>$commentinfo['videoid'] ));
			
			DI()->notorm->video_comments
				->where("id = '{$commentid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$video=DI()->notorm->video_comments
				->select("likes")
				->where("id = '{$commentid}'")
				->fetchOne();

		//获取视频信息
//		$videoinfo=DI()->notorm->video->select("thumb")->where("id='{$commentinfo['videoid']}'")->fetchOne();

		$rs['likes']=$video['likes'];

		return $rs; 		
	}
	
	/* 热门视频 */
	public function getVideoList($uid,$p){

        if($p<1){
            $p=1;
        }
		$nums=20;
		$start=($p-1)*$nums;

		$videoids_s='';
		$where="isdel=0 and status=1 and is_ad=0";  //上架且审核通过
		
		$video=DI()->notorm->video
				->select("*,(views + virtual_views) AS views_total")
				->where($where)
				->order("views_total desc")
				->limit($start,$nums)
				->fetchAll();

		foreach($video as $k=>$v){
			
			$v=handleVideo($uid,$v);
            
            $video[$k]=$v;

		}


		return $video;
	}


	/* 关注人视频 */
	public function getAttentionVideo($uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=20;
		$start=($p-1)*$nums;
		
		$video=array();
		$attention=DI()->notorm->user_attention
				->select("touid")
                ->where("uid='{$uid}' and status=1")
				->fetchAll();
		
		if($attention){
			
			$uids=array_column($attention,'touid');
			$touids=implode(",",$uids);
			
//			$videoids_s=getVideoBlack($uid);
//			$where="uid in ({$touids}) and id not in ({$videoids_s})  and isdel=0 and status=1";
			$where="uid in ({$touids}) and isdel=0 and status=1";

			$video=DI()->notorm->video
					->select("*")
					->where($where)
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();


			if(!$video){
				return 0;
			}
			
			foreach($video as $k=>$v){
				$v=handleVideo($uid,$v);
            
                $video[$k]=$v;
				
			}				
			
		}
		

		return $video;		
	}

	/* 获取我的广告视频 */
	public function getAdVideo($uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=20;
		$start=($p-1)*$nums;

        $where="uid = {$uid} and isdel=0 and status=1 and is_ad=1 and is_admin=0";

        $video=DI()->notorm->video
            ->select("*")
            ->where($where)
            ->order("addtime desc")
            ->limit($start,$nums)
            ->fetchAll();

        foreach($video as $k=>$v){
            $v=handleVideo($uid,$v);

            $video[$k]=$v;

        }


        return $video;
	}
	
	/* 视频详情 */
	public function getVideo($uid,$videoid){
		$video=DI()->notorm->video
					->select("*")
					->where("id = {$videoid}")
					->fetchOne();
		if(!$video){
			return 1000;
		}
        if(empty($uid)){
            $uid = 0;
        }
        $infoConfigPub = getConfigPub();
        $infoConfigPir = getConfigPri();
        $userVip = getUserVip($uid);
        $status = 0;
        $count_status = 0;
        $now = time();
        $msg = '';
        $todayMidnight = strtotime("today midnight", $now);
        if($infoConfigPir['vip_switch'] == 1){
            if($infoConfigPir['video_views_mode']==1){
                if($userVip['type']!=1){
                    $status = 1;
                    $msg = T('非VIP用户，');
                }
            }else{
                if($userVip['type']!=1) {
                    $count_status = 1;
                }
            }
        }else{
            if($infoConfigPir['video_views_mode']==1){
                $status = 1;
            }else{
                if($infoConfigPir['free_video_views_number']!=0){
                    $count_status = 1;
                }
            }
        }
        if($status == 1 && !empty($video['coin']) && $uid!=$video['uid']){
            if($uid == 0){
                return ['code'=>1001,'msg'=>T('请先登录')];
            }
            $video_coin=DI()->notorm->video_coin
                ->where("uid = {$uid} and videoid = {$videoid}")
                ->fetchOne();
            if(!$video_coin){
                return ['code'=>1001,'msg'=>$msg.T('观看视频需要付费{coin}{name_coin}，是否确定观看',['coin'=>$video['coin'],'name_coin'=>$infoConfigPub['name_coin']])];
            }
        }
//        if($count_status == 1){
//            if($uid == 0){
//                $name_view = 'video_view_'.date('Ymd',$now);
//                if(empty($_SESSION[$name_view])){
//                    $_SESSION[$name_view] = 1;
//                }
//                if($_SESSION[$name_view] >= $infoConfigPir['free_video_views_number']) {
//                    return ['code' => 1001, 'msg' => T('你已达当天最大免费观看视频次数，无法继续观看')];
//                }
//                $_SESSION[$name_view] = $_SESSION[$name_view]+1;
//            }else{
//                $videoViewCount = DI()->notorm->video_view
//                    ->where("uid = {$uid} and videoid = {$videoid} and addtime > $todayMidnight")
//                    ->count();
//                if ($videoViewCount >= $infoConfigPir['free_video_views_number']) {
//                    return ['code' => 1001, 'msg' => T('你已达当天最大免费观看视频次数，无法继续观看')];
//                }
//            }
//        }

		
		$video=handleVideoInfo($uid,$video);
		
		return 	$video;
	}

	/* 视频详情 */
	public function setVideoCoin($uid,$videoid){
		$video=DI()->notorm->video
					->select("coin,uid")
					->where("id = {$videoid}")
					->fetchOne();
		if(!$video){
			return 1000;
		}
        if(!empty($video['coin'])){
            if($uid==$video['uid']){
                return ['code'=>0,'msg'=>'自己的视频无需付费'];
            }
            $video_coin=DI()->notorm->video_coin
                ->where("uid = {$uid} and videoid = {$videoid}")
                ->fetchOne();
            if(!$video_coin){
                $data = [];
                $data['uid'] = $uid;
                $data['videoid'] = $videoid;
                $data['coin'] = $video['coin'];
                $data['addtime'] = time();
                $result= DI()->notorm->video_coin->insert($data);
                if($result){
                    DI()->notorm->user
                        ->where('id = ?', $uid)
                        ->update(array('coin' => new NotORM_Literal("coin - {$video['coin']}")));
                    //支出用户记录
                    $type='0';
                    $action='22';
                    $giftid=$result['id'];
                    $giftcount=1;
                    $total=$video['coin'];
                    $showid=0;
                    $addtime=$data['addtime'];
                    $insert=array("type"=>$type,"action"=>$action,"uid"=>$uid,"touid"=>$video['uid'],"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime );
                    DI()->notorm->user_coinrecord->insert($insert);


                    // 打赏主播
                    $action = 6;
                    $fromidid = $uid;
                    rewardStreamer($total,$uid,$fromidid,$action,$giftid,$giftcount,$showid);

                    $userinfo2 =DI()->notorm->user
                        ->select('consumption')
                        ->where('id = ?', $uid)
                        ->fetchOne();
                    insertMiningMachine($userinfo2['consumption'],$total,$uid);
                }
                return ['code'=>0,'msg'=>'付费成功'];
            }else{
                return ['code'=>0,'msg'=>'你已付费'];
            }
        }else{
            return ['code'=>0,'msg'=>'视频不需要付费'];
        }
	}
	
	/* 评论列表 */
	public function getComments($uid,$videoid,$p){
        if($p<1){
            $p=1;
        }
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->video_comments
					->select("*")
					->where("videoid='{$videoid}' and parentid='0'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();
		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=getUserInfo($v['uid'],1);
			$comments[$k]['datetime']=datetime($v['addtime']);	
			$comments[$k]['likes']=NumberFormat($v['likes']);	
			if($uid){
				$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);	
			}else{
				$comments[$k]['islike']='0';	
			}
			
			if($v['touid']>0){
				$touserinfo=getUserInfo($v['touid'],1);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			$comments[$k]['touserinfo']=$touserinfo;

			$count=DI()->notorm->video_comments
					->where("commentid='{$v['id']}'")
					->count();
			$comments[$k]['replys']=$count;
            
            /* 回复 */
            $reply=DI()->notorm->video_comments
					->select("*")
					->where("commentid='{$v['id']}'")
					->order("addtime desc")
					->limit(0,1)
					->fetchAll();
            foreach($reply as $k1=>$v1){
                
                $v1['userinfo']=getUserInfo($v1['uid'],1);
                $v1['datetime']=datetime($v1['addtime']);	
                $v1['likes']=NumberFormat($v1['likes']);	
                $v1['islike']=(string)$this->ifCommentLike($uid,$v1['id']);
                if($v1['touid']>0){
                    $touserinfo=getUserInfo($v1['touid'],1);
                }
                if(!$touserinfo){
                    $touserinfo=(object)array();
                    $v1['touid']='0';
                }
                
                if($v1['parentid']>0 && $v1['parentid']!=$v['id']){
                    $tocommentinfo=DI()->notorm->video_comments
                        ->select("content,at_info")
                        ->where("id='{$v1['parentid']}'")
                        ->fetchOne();
                }else{
                    $tocommentinfo=(object)array();
                    $touserinfo=(object)array();
                    $v1['touid']='0';
                }
                $v1['touserinfo']=$touserinfo;
                $v1['tocommentinfo']=$tocommentinfo;


                $reply[$k1]=$v1;
            }
            
            $comments[$k]['replylist']=$reply;
		}
		
		$commentnum=DI()->notorm->video_comments
					->where("videoid='{$videoid}'")
					->count();
		
		$rs=array(
			"comments"=>$commentnum,
			"commentlist"=>$comments,
		);
		
		return $rs;
	}

	/* 回复列表 */
	public function getReplys($uid,$commentid,$p){
        if($p<1){
            $p=1;
        }
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->video_comments
					->select("*")
					->where("commentid='{$commentid}'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();


		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=getUserInfo($v['uid'],1);
			$comments[$k]['datetime']=datetime($v['addtime']);	
			$comments[$k]['likes']=NumberFormat($v['likes']);	
			$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);
			if($v['touid']>0){
				$touserinfo=getUserInfo($v['touid'],1);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			


			if($v['parentid']>0 && $v['parentid']!=$commentid){
				$tocommentinfo=DI()->notorm->video_comments
					->select("content,at_info")
					->where("id='{$v['parentid']}'")
					->fetchOne();
			}else{

				$tocommentinfo=(object)array();
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';

			}
			$comments[$k]['touserinfo']=$touserinfo;
			$comments[$k]['tocommentinfo']=$tocommentinfo;
		}
		
		return $comments;
	}
	
	
	
	/* 评论/回复 是否点赞 */
	public function ifCommentLike($uid,$commentid){
		$like=DI()->notorm->video_comments_like
				->select("id")
				->where("uid='{$uid}' and commentid='{$commentid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 我的视频 */
	public function getMyVideo($uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=20;
		$start=($p-1)*$nums;
		
		$video=DI()->notorm->video
				->select("*")
				->where('uid=?  and isdel=0',$uid)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();
		
		foreach($video as $k=>$v){
            
            $xiajia_reason=$v['xiajia_reason'];
			$v=handleVideo($uid,$v);
            $v['xiajia_reason']=$xiajia_reason;
            
            $video[$k]=$v;
			
		}

				
		return $video;
	} 	
	/* 删除视频 */
	public function del($uid,$videoid){
		
		$result=DI()->notorm->video
					->where("id='{$videoid}' and uid='{$uid}'")
					->update( array( 'isdel'=>1 ) );
		if($result){
			// 删除 评论记录
			 /*DI()->notorm->video_comments
						->where("videoid='{$videoid}'")
						->delete(); 
			//删除视频评论喜欢
			DI()->notorm->video_comments_like
						->where("videoid='{$videoid}'")
						->delete(); 
			
			// 删除  点赞
			 DI()->notorm->video_like
						->where("videoid='{$videoid}'")
						->delete(); 
			//删除视频举报
			DI()->notorm->video_report
						->where("videoid='{$videoid}'")
						->delete(); 
			// 删除视频 
			 DI()->notorm->video
						->where("id='{$videoid}'")
						->delete();	*/ 

			//将喜欢的视频列表状态修改
			DI()->notorm->video_like
				->where("videoid='{$videoid}'")
				->update(array("status"=>0));	
		}				
		return 0;
	}	

	/* 个人主页视频 */
	public function getHomeVideo($uid,$touid,$p){
        if($p<1){
            $p=1;
        }
		$nums=21;
		$start=($p-1)*$nums;
		
		
		if($uid==$touid){  //自己的视频（需要返回视频的状态前台显示）
			$where=" uid={$uid} and isdel='0' and status=1";
		}else{  //访问其他人的主页视频
            $videoids_s=getVideoBlack($uid);
			$where="id not in ({$videoids_s}) and uid={$touid} and isdel='0' and status=1";
		}
		
		
		$video=DI()->notorm->video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();

		foreach($video as $k=>$v){
			$v=handleVideo($uid,$v);
            
            $video[$k]=$v;
		}			

		return $video;
		
	}

	/* 个人主页喜欢的视频 */
	public function getHomeLikeVideo($uid,$touid,$p){
        if($p<1){
            $p=1;
        }
		$nums=21;
		$start=($p-1)*$nums;

        $video = [];
        $touids=DI()->notorm->video_like
            ->select("videoid,addtime")
            ->where("uid='{$touid}' and status=1")
            ->order("addtime desc")
            ->limit($start,$nums)
            ->fetchAll();
        if($touids){
            $videoids=array_column($touids,'videoid');
            $videoids=implode(",",$videoids);
            $where="id in ({$videoids}) and isdel='0' and status=1";
            $video=DI()->notorm->video
                ->select("*")
                ->where($where)
                ->order("field(id,$videoids)")
                ->fetchAll();
            foreach($video as $k=>$v){
                $v=handleVideo($uid,$v);
                $video[$k]=$v;
            }
        }
		return $video;

	}

	/* 个人主页收藏的视频 */
	public function getHomeCollectVideo($uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=21;
		$start=($p-1)*$nums;

        $video = [];
        $videoids=DI()->notorm->video_collection
            ->select("videoid,addtime")
            ->where("uid='{$uid}' and status=1")
            ->order("addtime desc")
            ->limit($start,$nums)
            ->fetchAll();
        if($videoids){
            $videoids=array_column($videoids,'videoid');
            $videoids=implode(",",$videoids);
            $where="id in ({$videoids}) and isdel='0' and status=1";
            $video=DI()->notorm->video
                ->select("*")
                ->where($where)
                ->order("field(id,$videoids)")
                ->fetchAll();
            foreach($video as $k=>$v){
                $v=handleVideo($uid,$v);
                $video[$k]=$v;
            }
        }
		return $video;

	}
	/* 举报 */
	public function report($data) {
		
		$video=DI()->notorm->video
					->select("uid")
					->where("id='{$data['videoid']}'")
					->fetchOne();
		if(!$video){
			return 1000;
		}
		
		$data['touid']=$video['uid'];
					
		$result= DI()->notorm->video_report->insert($data);
		return 0;
	}	

	public function getRecommendVideos($uid,$p,$isstart){
        if($p<1){
            $p=1;
        }
		$pnums=20;
		$start=($p-1)*$pnums;

		$configPri=getConfigPri();
		$video_showtype=$configPri['recommended_video_display_methods'];


        // 移除不感兴趣
        DI()->redis->select(1);
        $unconcernLists = [];
        if($uid){
            $key = 'unconcern_'.$uid;
            // 向列表添加元素
            $unconcernLists=DI()->redis -> Get($key);
            if($unconcernLists){
                $unconcernLists = json_decode($unconcernLists,true);
            }
        }

		if($video_showtype==0){ //随机

			if($p==1){
				DI()->redis -> del('readvideo_'.$uid);
			}

			//去除看过的视频
			$where=array();
            if($uid) {
                $readLists = DI()->redis->Get('readvideo_' . $uid);
                if ($readLists) {
                    $where = json_decode($readLists, true);
                    if (count($where) > 300) {
                        DI()->redis->del('readvideo_' . $uid);
                    }
                }
                if ($unconcernLists) {
                    $where = array_merge($where, $unconcernLists);
                    $where = array_unique($where);
                }
            }

			$info=DI()->notorm->video
			->where("isdel=0 and status=1 and is_ad=0")
			->where('not id',$where)
			->order("rand()")
			->limit($pnums)
			->fetchAll();
			$where1=array();
			foreach ($info as $k => $v) {
				if(!in_array($v['id'],$where)){
					$where1[]=$v['id'];
				}
			}

			//将两数组合并
			$where2=array_merge($where,$where1);
            if($uid){
                DI()->redis -> set('readvideo_'.$uid,json_encode($where2));
            }

		}else{

			//获取私密配置里的评论权重和点赞权重
			$comment_weight=$configPri['comment_weight_value'];
			$like_weight=$configPri['like_weight_value'];
			$share_weight=$configPri['share_weight_value'];
			$initial_exposure=$configPri['initial_exposure_value'];

			//热度值 = 点赞数*点赞权重+评论数*评论权重+分享数*分享权重
			//转化率 = 完整观看次数/总观看次数
			//排序规则：（曝光值+热度值）*转化率
			//曝光值从视频发布开始，每小时递减1，直到0为止

			/*废弃$info=DI()->notorm->video->queryAll("select *,format(watch_ok/views,2) as aaa, (ceil(comments *".$comment_weight." + likes *".$like_weight." + shares *".$share_weight.") )*format(watch_ok/views,2) as recomend from ".$prefix."video where isdel=0 and status=1  order by recomend desc,addtime desc limit ".$start.",".$pnums);*/

			$info=DI()->notorm->video
            ->select("*,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight." + views * ".$initial_exposure."))* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend")
            ->where("isdel=0 and status=1 and is_ad=0")
            ->order("recomend desc,addtime desc")
            ->limit($start,$pnums)
            ->fetchAll();
		}

        $time = time();
        // 广告视频输出
        $advertising_video=$configPri['advertising_video_switch'];
        if($advertising_video==1){
            $advertising_video_polling=$configPri['advertising_video_polling_switch'];
            if($advertising_video_polling==1){
                $pnum=$pnums/$configPri['advertising_video_polling_number'];
            }else{
                $pnum=rand(1,10);
            }
            $start=($p-1)*$pnum;
            $advertList=DI()->notorm->video
                ->select('*')
                ->where("isdel=0 and status=1 and is_ad=1 and ad_endtime>$time")
//                ->where("isdel=0 and status=1 and is_ad=1 and is_admin=1 and ad_endtime>$time")
                ->order("orderno desc")
                ->limit($start,$pnum)
                ->fetchAll();
            $infoAdvertList = [];
            $infoAll = [];
            foreach ($advertList as $k => $v) {
                if($advertising_video_polling==1){
                    $a = $k*$configPri['advertising_video_polling_number']+$configPri['advertising_video_polling_number'];
                }else{
                    $a = $k*$pnum+$pnum;
                }
                $infoAdvertList[$a]=$advertList[$k];
            }
            if($unconcernLists){
                foreach ($infoAdvertList as $k => $v) {
                    if(in_array($v['id'],$unconcernLists)){
                        unset($infoAdvertList[$k]);
                    }
                }
            }
            foreach ($info as $k => $v) {
                if(!$infoAdvertList[$k]){
                    $infoAll[] = $v;
                }else{
                    $infoAll[] = $infoAdvertList[$k];
                    $infoAll[] = $v;
                }
            }
            $info = $infoAll;
        }
        $videoidArrayInfo=array_column($info,"id");

        // 上热门视频输出
        $popular_video_addition_interval = $configPri['popular_video_addition_interval'];
        $pnum=$pnums/$popular_video_addition_interval;
        $start=($p-1)*$pnum;
        $popularList=DI()->notorm->popular
            ->select('id,uid,videoid,duration,actual_view_counts,view_counts,price,addtime')
            ->where("status=0")
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();
        $videoidArray = [];
        foreach ($popularList as $k => $v) {
            $durationTime = (int)$v['addtime']+$v['duration']*60*60;
            if($durationTime > $time && $v['actual_view_counts'] < $v['view_counts']){
                if($unconcernLists){
                    if(!in_array($v['videoid'],$unconcernLists)){
                        $videoidArray[] = $v['videoid'];
                    }
                }else{
                    $videoidArray[] = $v['videoid'];
                }
            }else{
                // 更新上热门信息
                if($v['actual_view_counts'] < $v['view_counts']){
                    // 未达到预计播放量 退还金额
                    $return_price = $v['price']*($v['view_counts']-$v['actual_view_counts'])/$v['view_counts'];
                    $return_price = (int)$return_price;
                    $data_popular = array(
                        'status' => 1,
                        'return_price' => $return_price,
                    );
                    DI()->notorm->user
                        ->where('id = ?', $v['uid'])
                        ->update(array('coin' => new NotORM_Literal("coin + {$return_price}")));

                    $insert=array(
                        "type"=>1,
                        "action"=>26,
                        "uid"=>$v['uid'],
                        "touid"=>$v['uid'],
                        "giftid"=>0,
                        "giftcount"=>1,
                        "totalcoin"=>$return_price,
                        "showid"=>0,
                        "addtime"=>$time
                    );
                    DI()->notorm->user_coinrecord->insert($insert);
                }else{
                    $data_popular = array(
                        'status' => 1,
                    );
                }
                DI()->notorm->popular
                    ->where("id = '{$v['id']}'")
                    ->update($data_popular);
            }
        }

        //移除相同的视频
        $infoAll = [];
        $intersect = array_intersect($videoidArray, $videoidArrayInfo);
        foreach ($info as $v) {
            if(!in_array($v['id'],$intersect)){
                $infoAll[]=$v;
            }
        }
        $info = $infoAll;

        $videoids=implode(",",$videoidArray);
        if(empty($videoids)){
            $popularVideoList=[];
        }else{
            $popularVideoList=DI()->notorm->video
                ->select('*')
                ->where("id in ($videoids)")
                ->order("field(id,$videoids)")
                ->limit($start,$pnum)
                ->fetchAll();
        }
        $infoPopularVideoList = [];
        $infoAll = [];
        foreach ($popularVideoList as $k => $v) {
            $a = $k*$popular_video_addition_interval+$popular_video_addition_interval;
            $infoPopularVideoList[$a]=$v;
        }
        foreach ($info as $k => $v) {
            if(!$infoPopularVideoList[$k]){
                $infoAll[] = $v;
            }else{
                $infoAll[] = $infoPopularVideoList[$k];
                unset($infoPopularVideoList[$k]);
                $infoAll[] = $v;
            }
        }
        $info = $infoAll;
        foreach ($info as $k => $v) {
            $v=handleVideo($uid,$v);
            $info[$k]=$v;
        }
		if(!$info){
            return 1001;
		}


		return $info;
	}

	/*获取附近的视频*/
	public function getNearby($uid,$lng,$lat,$p){
        if($p<1){
            $p=1;
        }
		$pnum=20;
		$start=($p-1)*$pnum;

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		$info=DI()->notorm->video->queryAll("select *, round(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ".$lat." * PI() / 180 - lat * PI() / 180) / 2),2) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * POW(SIN((".$lng." * PI() / 180 - lng * PI() / 180) / 2),2))) * 1000) AS distance FROM ".$prefix."video  where uid !=".$uid." and isdel=0 and status=1  and is_ad=0 order by distance asc,addtime desc limit ".$start.",".$pnum);

		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
            
            $v=handleVideo($uid,$v);
            $v['distance']=distanceFormat($v['distance']);
            
            $info[$k]=$v;
			
		}
		
		return $info;
	}

	/* 举报分类列表 */
	public function getReportContentlist() {
		
		$reportlist=DI()->notorm->video_report_classify
					->select("*")
					->order("list_order asc")
					->fetchAll();
		if(!$reportlist){
			return 1001;
		}
        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('video_report_classify', 'name', $lang);
        }
        foreach ($reportlist as $k=>$v){
            if($lang=='en'){
                $reportlist[$k]['name']=$v['name_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $reportlist[$k]['name']=$translate[$v['id']];
                    }
                }
            }
        }
		
		return $reportlist;
		
	}

	/*更新视频看完次数*/
	public function setConversion($videoid){


		//更新视频看完次数
		$res=DI()->notorm->video
				->where("id = '{$videoid}' and isdel=0 and status=1")
				->update( array('watch_ok' => new NotORM_Literal("watch_ok + 1") ) );

		return 1;
	}	

	
	/* 分类视频 */
	public function getClassVideo($videoclassid,$uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=21;
		$start=($p-1)*$nums;
		$where="  isdel='0' and status=1  and classid={$videoclassid}";
		
		$video=DI()->notorm->video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();

		
		foreach($video as $k=>$v){
			$v=handleVideo($uid,$v);
            
            $video[$k]=$v;
		}			

		return $video;
		
	}
	/* 音乐视频 */
	public function getMusicVideo($music_id,$uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=21;
		$start=($p-1)*$nums;
		$where="  isdel='0' and status=1  and music_id={$music_id}";

		$video=DI()->notorm->video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();


		foreach($video as $k=>$v){
			$v=handleVideo($uid,$v);

            $video[$k]=$v;
		}

		return $video;

	}
	/* 话题视频 */
	public function getDynamicLabelVideo($dynamic_label_id,$uid,$p){
        if($p<1){
            $p=1;
        }
		$nums=21;
		$start=($p-1)*$nums;
		$where="  isdel='0' and status=1  and dynamic_label_id={$dynamic_label_id}";

		$video=DI()->notorm->video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();


		foreach($video as $k=>$v){
			$v=handleVideo($uid,$v);

            $video[$k]=$v;
		}

		return $video;

	}
	
	/*删除评论 删除子级评论*/
	public function delComments($uid,$videoid,$commentid,$commentuid) {
       $result=DI()->notorm->video
					->select("uid")
					->where("id='{$videoid}'")
					->fetchOne();	
					
		if(!$result){
			return 1001;
		}			
		
		
		if($uid!=$commentuid){
			if($uid!=$result['uid']){
				return 1002;
			}
		}


        try {
            DI()->notorm->beginTransaction('db_appapi');

            // 删除 评论记录
            DI()->notorm->video_comments
                ->where("id='{$commentid}'")
                ->delete();
            //删除视频评论喜欢
            DI()->notorm->video_comments_like
                ->where("commentid='{$commentid}'")
                ->delete();
            /* 更新 视频 */
            DI()->notorm->video
                ->where("id = '{$videoid}' and comments>0")
                ->update(array('comments' => new NotORM_Literal("comments - 1")));


            //删除相关的子级评论
            $lists = DI()->notorm->video_comments
                ->select("*")
                ->where("commentid='{$commentid}' or parentid='{$commentid}'")
                ->fetchAll();
            foreach ($lists as $k => $v) {
                //删除 评论记录
                DI()->notorm->video_comments
                    ->where("id='{$v['id']}'")
                    ->delete();
                //删除视频评论喜欢
                DI()->notorm->video_comments_like
                    ->where("commentid='{$v['id']}'")
                    ->delete();

                /* 更新 视频 */
                DI()->notorm->video
                    ->where("id = '{$v['videoid']}' and comments>0")
                    ->update(array('comments' => new NotORM_Literal("comments - 1")));
            }
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
			
		
						
		return 0;

    }

	/*处理用户每观看60秒视频奖励红包映票逻辑*/
	public function dealUserVideoRewards($uid,$time) {
        $configPri= getConfigPri();
        if($configPri['video_rewards_switch']==1){
            $now = time();
            $data_day = date('Ymd',$now);
            $key = 'user_video_rewards_time_'.$data_day.$uid;
            $incrTime = DI()->redis->incr($key,$time);
            DI()->redis->expire($key, 60*60*24);
            if($incrTime>60){
                DI()->redis->del($key);
                $todayMidnight = strtotime("today midnight", $now);
                $tomorrowMidnight = strtotime("tomorrow midnight", $now);
                $total = DI()->notorm->user_voterecord->where("action = 11 and addtime > $todayMidnight and addtime < $tomorrowMidnight")->sum('total');
                if(empty($total)){
                    $total = 0;
                }
                if($total<$configPri['video_rewards_max_number']){
                    $red_votes=$configPri['video_rewards_number'];
                    /* 增加用户映票 */
                    $isprofit =DI()->notorm->user
                        ->where('id = ?', $uid)
                        ->update( array('red_votes' => new NotORM_Literal("red_votes + {$red_votes}") ));
                    if($isprofit){  //生成记录
                        $insert=array(
                            "type"=>'1',
                            "action"=>'11',
                            "uid"=>$uid,
                            "fromid"=>$uid,
                            "nums"=>'1',
                            "total"=>$red_votes,
                            "votes"=>$red_votes,
                            "addtime"=>time()
                        );
                        DI()->notorm->user_voterecord->insert($insert);
                    }
                }
            }
        }
        return 0;
    }

}
