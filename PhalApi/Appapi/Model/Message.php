<?php

class Model_Message extends PhalApi_Model_NotORM {
	/* 最新消息 */
	public function getNews($uid) {
        
		$info=DI()->notorm->pushrecord
                    ->select('title,content,addtime')
                    ->where("touid = '{$uid}'")
                    ->order('addtime desc')
                    ->fetchOne();

//        $info['count']=DI()->notorm->pushrecord
//                    ->select('title,content,addtime')
//                    ->where("touid = '{$uid}' and is_read = 0")
//                    ->count();

		return $info;
	}

	/* 信息列表 */
	public function getList($uid,$p) {
        if($p<1){
            $p=1;
        }
		$pnum=50;
		$start=($p-1)*$pnum;

		$list=DI()->notorm->pushrecord
                    ->select('id,title,content,addtime,is_read,touid')
//                    ->where("(type=0 and (touid='' or ( touid!='' and (touid = '{$uid}' or touid like '{$uid},%' or touid like '%,{$uid},%' or touid like '%,{$uid}') ))) or (type=1 and touid='{$uid}')")
                    ->where("(type=0 and (touid='' or touid = '{$uid}')) or (type=1 and touid='{$uid}')")
                    ->order('addtime desc')
                    ->limit($start,$pnum)
                    ->fetchAll();
//        foreach($list as $k=>$v){
//            if($v['is_read']==0 && $v['touid']==$uid) {
//                DI()->notorm->pushrecord
//                    ->where('id',$v['id'])
//                    ->update(array('is_read'=>1));
//                $list[$k]['is_read']='1';
//            }
//        }

		return $list;
	}

    //店铺订单信息列表
    public function getShopOrderList($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;

        $list=DI()->notorm->shop_order_message
                ->select("title,orderid,addtime,type,is_commission")
                ->where("uid=?",$uid)
                ->order("addtime desc")
                ->limit($start,$pnum)
                ->fetchAll();

        foreach ($list as $k => $v) {
            $list[$k]['addtime']=date("Y-m-d H:i",$v['addtime']);
            $list[$k]['avatar']=get_upload_path('/orderMsg.png');

            $where['id']=$v['orderid'];
            $order_info=getShopOrderInfo($where,'status');
            $list[$k]['status']=$order_info['status'];
        }

        return $list;
    }		

}
