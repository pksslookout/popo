<?php

class Domain_User {

	public function getBaseInfo($userId) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBaseInfo($userId);

			return $rs;
	}
	
	public function getBaseInfoCount($userId) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBaseInfoCount($userId);

			return $rs;
	}

	public function getUserLevel($userId) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getUserLevel($userId);

			return $rs;
	}
	public function checkName($uid,$name) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->checkName($uid,$name);

			return $rs;
	}
	
	public function userUpdate($uid,$fields) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->userUpdate($uid,$fields);

			return $rs;
	}

	public function userUpdateMobile($uid,$fields) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->userUpdateMobile($uid,$fields);

			return $rs;
	}
	
	public function updatePass($uid,$old_pass,$pass) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->updatePass($uid,$old_pass,$pass);

			return $rs;
	}

	public function updatePayPass($uid,$pass) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->updatePayPass($uid,$pass);

			return $rs;
	}

	public function updateBnbAdr($uid,$bnb_adr) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->updateBnbAdr($uid,$bnb_adr);

			return $rs;
	}

	public function getBalance($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBalance($uid);

			return $rs;
	}

	public function getMyUsdtInfo($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getMyUsdtInfo($uid);

			return $rs;
	}

	public function forwardChainUsdt($uid,$adr,$chainType,$number,$user_pay_pass) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->forwardChainUsdt($uid,$adr,$chainType,$number,$user_pay_pass);

			return $rs;
	}
	
	public function getChargeRules() {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getChargeRules();

			return $rs;
	}

	public function getVipChargeRules() {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getVipChargeRules();

			return $rs;
	}

	public function setVipBalance($data) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setVipBalance($data);

			return $rs;
	}
	
	public function getProfit($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getProfit($uid);

			return $rs;
	}

	public function getUsdtForward($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getUsdtForward($uid);

			return $rs;
	}

	public function getRedProfit($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getRedProfit($uid);

			return $rs;
	}

	public function setCash($data) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setCash($data);

			return $rs;
	}

	public function setRedCash($data) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setRedCash($data);

			return $rs;
	}
	
	public function setAttent($uid,$touid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setAttent($uid,$touid);

			return $rs;
	}
	
	public function setBlack($uid,$touid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setBlack($uid,$touid);

			return $rs;
	}
	
	public function getFollowsList($uid,$touid,$p,$user_nicename) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getFollowsList($uid,$touid,$p,$user_nicename);

			return $rs;
	}

	public function getLikesList($uid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getLikesList($uid,$p);

			return $rs;
	}

	public function getPopularVideoList($uid,$p,$status) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getPopularVideoList($uid,$p,$status);

			return $rs;
	}

	public function getPopularLiveList($uid,$p,$status) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getPopularLiveList($uid,$p,$status);

			return $rs;
	}

	public function getCommentsList($uid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getCommentsList($uid,$p);

			return $rs;
	}

	public function getAtsList($uid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getAtsList($uid,$p);

			return $rs;
	}
	
	public function getFansList($uid,$touid,$p,$status,$keyword) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getFansList($uid,$touid,$p,$status,$keyword);

			return $rs;
	}

	public function getBlackList($uid,$touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBlackList($uid,$touid,$p);

			return $rs;
	}

	public function getLiverecord($touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getLiverecord($touid,$p);

			return $rs;
	}
	
	public function getUserHome($uid,$touid) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->getUserHome($uid,$touid);
		return $rs;
	}	
	
	public function getContributeList($touid,$p) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->getContributeList($touid,$p);
		return $rs;
	}	
	
	public function setDistribut($uid,$code) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->setDistribut($uid,$code);
		return $rs;
	}

	public function getImpressionLabel() {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->getImpressionLabel();

        return $rs;
    }	

	public function getUserLabel($uid,$touid) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->getUserLabel($uid,$touid);

        return $rs;
    }	

	public function setUserLabel($uid,$touid,$labels) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->setUserLabel($uid,$touid,$labels);

        return $rs;
    }	

	public function getMyLabel($uid) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->getMyLabel($uid);

        return $rs;
    }	

	public function getPerSetting() {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->getPerSetting();

        return $rs;
    }	

	public function getUserAccountList($uid) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->getUserAccountList($uid);

        return $rs;
    }	

	public function getUserAccount($where) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->getUserAccount($where);

        return $rs;
    }	

	public function setUserAccount($data) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->setUserAccount($data);

        return $rs;
    }

	public function delUserAccount($data) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->delUserAccount($data);

        return $rs;
    }	
	
	public function LoginBonus($uid){
		$rs = array();
		$model = new Model_User();
		$rs = $model->LoginBonus($uid);
		return $rs;

	}

	public function getLoginBonus($uid){
		$rs = array();
		$model = new Model_User();
		$rs = $model->getLoginBonus($uid);
		return $rs;

	}

	public function checkIsAgent($uid){
		$rs = array();
		$model = new Model_User();
		$rs = $model->checkIsAgent($uid);
		return $rs;
	}
    
    //用户申请店铺余额提现
	public function setShopCash($data){
		$rs = array();

		$model = new Model_User();
		$rs = $model->setShopCash($data);

		return $rs;
	}

	public function getAuthInfo($uid){
		$rs = array();

		$model = new Model_User();
		$rs = $model->getAuthInfo($uid);

		return $rs;
	}

	public function setAuthInfo($data){
		$rs = array();

		$model = new Model_User();
		$rs = $model->setAuthInfo($data);

		return $rs;
	}

	public function seeDailyTasks($uid,$type){
		$rs = array();

		$model = new Model_User();
		$rs = $model->seeDailyTasks($uid,$type);

		return $rs;
	}

	public function receiveTaskReward($uid,$taskid){
		$rs = array();

		$model = new Model_User();
		$rs = $model->receiveTaskReward($uid,$taskid);

		return $rs;
	}

    public function getUserVip($userId) {
        $rs = array();
        $model = new Model_User();
        $rs = $model->getUserVip($userId);
        return $rs;
    }

    public function getAgent($userId) {
        $rs = array();
        $model = new Model_User();
        $rs = $model->getAgent($userId);
        return $rs;
    }

    public function getReportUserClassify($classifyid) {
        $rs = array();
        $model = new Model_User();
        $rs = $model->getReportUserClassify($classifyid);
        return $rs;
    }

    public function report($data) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->report($data);

        return $rs;
    }

    public function getLangList() {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getLangList();

        return $rs;
    }

    public function getVideoView($uid,$p) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getVideoView($uid,$p);

        return $rs;
    }

    public function getChangeUserList($uid,$p,$source) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getChangeUserList($uid,$p,$source);

        return $rs;
    }

    public function getChangeUserUsdtList($uid,$p) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getChangeUserUsdtList($uid,$p);

        return $rs;
    }

    public function getEarningsList($uid,$p) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getEarningsList($uid,$p);

        return $rs;
    }

    public function getCashList($uid,$p) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getCashList($uid,$p);

        return $rs;
    }

    public function getUsdtList($uid,$p) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->getUsdtList($uid,$p);

        return $rs;
    }

    public function delVideoView($uid,$ids) {
        $rs = array();

        $model = new Model_User();
        $rs = $model->delVideoView($uid,$ids);

        return $rs;
    }

    public function checkTeenager($uid){
        $rs = array();

        $model = new Model_User();
        $rs = $model->checkTeenager($uid);

        return $rs;
    }

    public function setTeenagerPassword($uid,$password,$type){
        $rs = array();

        $model = new Model_User();
        $rs = $model->setTeenagerPassword($uid,$password,$type);

        return $rs;
    }

    public function updateTeenagerPassword($uid,$oldpassword,$password){
        $rs = array();

        $model = new Model_User();
        $rs = $model->updateTeenagerPassword($uid,$oldpassword,$password);

        return $rs;
    }

    public function closeTeenager($uid,$password){
        $rs = array();

        $model = new Model_User();
        $rs = $model->closeTeenager($uid,$password);

        return $rs;
    }

    public function addTeenagerTime($uid){
        $rs = array();

        $model = new Model_User();
        $rs = $model->addTeenagerTime($uid);

        return $rs;
    }

    public function updateBgImg($uid,$img){
        $rs = array();
        $model = new Model_User();
        $rs = $model->updateBgImg($uid,$img);
        return $rs;
    }

    public function checkTeenagerIsOvertime($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->checkTeenagerIsOvertime($uid);
        return $rs;
    }

    public function getConversionInfo($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getConversionInfo($uid);
        return $rs;
    }

    public function setConversion($uid,$conversion_source,$conversion_location,$number){
        $rs = array();
        $model = new Model_User();
        $rs = $model->setConversion($uid,$conversion_source,$conversion_location,$number);
        return $rs;
    }

    public function getConversionList($uid,$type,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getConversionList($uid,$type,$p);
        return $rs;
    }

    public function getMineMachineInfo($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getMineMachineInfo($uid);
        return $rs;
    }

    public function getPopoInfo($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getPopoInfo($uid);
        return $rs;
    }

    public function getMineMachineList($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getMineMachineList($uid);
        return $rs;
    }

    public function getMyMineMachineDividend($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getMyMineMachineDividend($uid);
        return $rs;
    }

    public function getMyMineMachineList($uid,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getMyMineMachineList($uid,$p);
        return $rs;
    }

    public function getMyMineMachineRewardList($uid,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getMyMineMachineRewardList($uid,$p);
        return $rs;
    }

    public function getMyCoinRewardList($uid,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getMyCoinRewardList($uid,$p);
        return $rs;
    }

    public function setTransferPoPoDividendToCurrency($uid,$number){
        $rs = array();
        $model = new Model_User();
        $rs = $model->setTransferPoPoDividendToCurrency($uid,$number);
        return $rs;
    }

    public function getPoPoDividendList($uid,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getPoPoDividendList($uid,$p);
        return $rs;
    }

    public function getLalaList($uid,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getLalaList($uid,$p);
        return $rs;
    }

    public function getScoreList($uid,$p){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getScoreList($uid,$p);
        return $rs;
    }

    public function getScoreInfo($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getScoreInfo($uid);
        return $rs;
    }

    public function getScoreEarningsInfo($uid){
        $rs = array();
        $model = new Model_User();
        $rs = $model->getScoreEarningsInfo($uid);
        return $rs;
    }

}
