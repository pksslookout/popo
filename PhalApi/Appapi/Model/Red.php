<?php

class Model_Red extends PhalApi_Model_NotORM {
	/* 发布红包 */
	public function sendRed($data) {

        $rs['code'] = 1009;
        $rs['msg'] = T('服务暂停');
        return $rs;
        try {
            DI()->notorm->beginTransaction('db_appapi');
            $rs = array('code' => 0, 'msg' => T('发送成功'), 'info' => array());

            $uid = $data['uid'];
            $total = $data['coin'];
            $ifok = DI()->notorm->user
                ->where('id = ? and coin >= ?', $uid, $total)
                ->update(array('coin' => new NotORM_Literal("coin - {$total}"), 'consumption' => new NotORM_Literal("consumption + {$total}")));
            if (!$ifok) {
                DI()->notorm->rollback('db_appapi');
                $rs['code'] = 1009;
                $rs['msg'] = T('余额不足');
                return $rs;
            }

            $result = DI()->notorm->red->insert($data);

            if (!$result) {
                DI()->notorm->rollback('db_appapi');
                $rs['code'] = 1009;
                $rs['msg'] = T('发送失败，请重试');
                return $rs;
            }

            $type = '0';
            $action = '8';
            $uid = $data['uid'];
            $giftid = $result['id'];
            $giftcount = 1;
            $total = $data['coin'];
            $showid = $data['showid'];
            $addtime = $data['addtime'];


            $insert = array("type" => $type, "action" => $action, "uid" => $uid, "touid" => $uid, "giftid" => $giftid, "giftcount" => $giftcount, "totalcoin" => $total, "showid" => $showid, "addtime" => $addtime);
            DI()->notorm->user_coinrecord->insert($insert);

            $rs['info'] = $result;
            DI()->notorm->commit('db_appapi');
            return $rs;
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
	}		
    
    /* 红包列表 */
    public function getRedList($liveuid,$showid){
        $list=DI()->notorm->red
                ->select("*")
                ->where('liveuid = ? and showid= ?',$liveuid,$showid)
                ->order('addtime desc')
                ->fetchAll();
        return $list;
    }

	/* 抢红包 */
	public function robRed($data) {

        $rs['code'] = 400;
        $rs['msg'] = T('服务暂停');
        return $rs;
        try {
            DI()->notorm->beginTransaction('db_appapi');
            $type = '1';
            $action = '9';
            $uid = $data['uid'];
            $giftid = $data['redid'];
            $giftcount = 1;
            $total = $data['coin'];
            $showid = $data['showid'];
            $addtime = $data['addtime'];
            unset($data['showid']);

            // 分离coin与red_coin所有此次无需记录
//        $insert=array("type"=>$type,"action"=>$action,"uid"=>$uid,"touid"=>$uid,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime );
//		DI()->notorm->user_coinrecord->insert($insert);

            $result = DI()->notorm->red_record->insert($data);


            DI()->notorm->user
                ->where('id = ?', $uid)
                ->update(array('red_votes' => new NotORM_Literal("red_coin + {$total}")));

            DI()->notorm->red
                ->where('id = ?', $giftid)
                ->update(array('coin_rob' => new NotORM_Literal("coin_rob + {$total}"), 'nums_rob' => new NotORM_Literal("nums_rob + 1")));

            DI()->notorm->commit('db_appapi');
            return $result;
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
	}			

    /* 抢红包列表 */
    public function getRedRobList($redid){
        $list=DI()->notorm->red_record
                ->select("*")
                ->where('redid = ?',$redid)
                ->order('addtime desc')
                ->fetchAll();
        return $list;
    }
    
    /* 红包信息 */
    public function getRedInfo($redid){
        $redinfo=DI()->notorm->red
                ->select("*")
                ->where('id = ? ',$redid )
                ->fetchOne();
        if($redinfo){
            unset($redinfo['showid']);
            unset($redinfo['liveuid']);
            unset($redinfo['effecttime']);
            unset($redinfo['addtime']);
            unset($redinfo['status']);
        }
        return $redinfo;
        
    }
}
