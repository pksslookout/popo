<?php

class Model_Useradvert extends PhalApi_Model_NotORM {

	public function getAdvertList($p) {
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;
		
        $advertList=DI()->notorm->user_advert
            ->select('*')
            ->where('is_status=?',1)
            ->order("id desc")
            ->limit($start,$pnum)
            ->fetchAll();

        return $advertList;
	}

	public function getAdvertiserInfo($uid) {

        $advertInfo=DI()->notorm->advertiser
            ->select('*')
            ->where('uid=?',$uid)
            ->fetchOne();

        return $advertInfo;
	}

	public function getAdvertCommentList($p, $advertid) {
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;

        $advertList=DI()->notorm->user_advert_comment
            ->select('*')
            ->where('useradvertid=?',$advertid)
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->fetchAll();

        return $advertList;
	}

	public function advertComment($data) {

        $user = DI()->notorm->user
            ->select("user_nicename")
            ->where('id=?',$data['uid'])
            ->fetchOne();

        $advert = DI()->notorm->user_advert
            ->select("*")
            ->where('id=?',$data['useradvertid'])
            ->fetchOne();
        if(!$advert){
            return '';
        }

        $data['user_nicename'] = $user['user_nicename'];
        $data['addtime'] = time();

        $rs=DI()->notorm->user_advert_comment->insert($data);
        if(!$rs){
            return '';
        }

        $rs=DI()->notorm->user_advert
            ->where('id=?',$data['useradvertid'])
            ->update(array('number_comment'=> new NotORM_Literal("number_comment + 1 ")));

        if(!$rs){
            return '';
        }

        return $rs;
	}

	public function likeAdvertComment($data) {

        $rs=DI()->notorm->user_advert_comment
            ->where('id=?',$data['id'])
            ->update(array('number_likes'=> new NotORM_Literal("number_likes + 1 ")));

        if(!$rs){
            return '';
        }

        return $rs;
	}

	public function likeAdvert($data) {

        $rs=DI()->notorm->user_advert
            ->where('id=?',$data['id'])
            ->update(array('number_likes'=> new NotORM_Literal("number_likes + 1 ")));

        if(!$rs){
            return '';
        }

        return $rs;
	}

	public function shareAdvert($data) {

        $rs=DI()->notorm->user_advert
            ->where('id=?',$data['id'])
            ->update(array('number_share'=> new NotORM_Literal("number_share + 1 ")));

        if(!$rs){
            return '';
        }

        return $rs;
	}

    public function applyAdvertiser($data) {

        $user = DI()->notorm->user
            ->select("user_nicename")
            ->where('id=?',$data['uid'])
            ->fetchOne();

        $data['user_nicename'] = $user['user_nicename'];
        $data['addtime'] = time();

        $rs=DI()->notorm->advertiser->insert($data);
        if(!$rs){
            return '';
        }

        return $rs;
    }

    public function addUserAdvert($data) {

        $user = DI()->notorm->user
            ->select("user_nicename")
            ->where('id=?',$data['uid'])
            ->fetchOne();

        $data['user_nicename'] = $user['user_nicename'];
        $data['addtime'] = time();

        $rs=DI()->notorm->user_advert->insert($data);
        if(!$rs){
            return '';
        }

        return $rs;
    }

}
