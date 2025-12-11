<?php

class Domain_Login {


    public function userLoginReg($country_code,$user_login,$source,$type,$agent_code) {
        $model = new Model_Login();
        $rs = $model->userLoginReg($country_code,$user_login,$source,$type,$agent_code);

        return $rs;
    }

    public function userLogin($country_code,$user_login,$user_pass,$type) {
        $model = new Model_Login();
        $rs = $model->userLogin($country_code,$user_login,$user_pass,$type);

        return $rs;
    }

    public function userReg($country_code,$user_login,$user_pass,$source,$type,$agent_code) {
        $model = new Model_Login();
        $rs = $model->userReg($country_code,$user_login,$user_pass,$source,$type,$agent_code);

        return $rs;
    }	
	
    public function userFindPass($country_code,$user_login,$user_pass,$type) {
        $model = new Model_Login();
        $rs = $model->userFindPass($country_code,$user_login,$user_pass,$type);

        return $rs;
    }

    public function upUserPush($uid,$pushid) {
        $model = new Model_Login();
        $rs = $model->upUserPush($uid,$pushid);

        return $rs;
    }			
	
	public function getUserban($user_login) {
        $model = new Model_Login();
        $rs = $model->getUserban($user_login);

        return $rs;
    }
	public function getThirdUserban($openid,$type) {
        $model = new Model_Login();
        $rs = $model->getThirdUserban($openid,$type);

        return $rs;
    }

    public function getCancelCondition($uid){
        $model = new Model_Login();
        $rs = $model->getCancelCondition($uid);

        return $rs;
    }

    public function cancelAccount($uid){
        $model = new Model_Login();
        $rs = $model->cancelAccount($uid);

        return $rs;
    }

}
