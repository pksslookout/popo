<?php

class Model_Charge extends PhalApi_Model_NotORM {
	/* 订单号 */
	public function getOrderId($changeid,$orderinfo) {
		
		$charge=DI()->notorm->charge_rules->select('*')->where('id=?',$changeid)->fetchOne();
		
		if(!$charge || $charge['money']!=$orderinfo['money'] || ($charge['coin']!=$orderinfo['coin']  && $charge['coin_ios']!=$orderinfo['coin'] )){
			return 1003;
		}
		
		$orderinfo['coin_give']=$charge['give'];
		

		$result= DI()->notorm->charge_user->insert($orderinfo);

		return $result;
	}
	/* vip订单号 */
	public function getVipOrderId($changeid,$orderinfo) {

		$charge=DI()->notorm->vip_charge_rules->select('*')->where('id=?',$changeid)->fetchOne();

		if(!$charge || $charge['money']!=$orderinfo['money']){
			return 1003;
		}

		$orderinfo['days']=$charge['days'];
		$orderinfo['ambient']=0;


		DI()->notorm->vip_charge_user->insert($orderinfo);

		return $charge;
	}

}
