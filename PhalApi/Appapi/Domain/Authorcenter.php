<?php

class Domain_Authorcenter {
	public function getAuthorCenterList($p) {
		$rs = array();

		$model = new Model_Authorcenter();
		$rs = $model->getAuthorCenterList($p);

		return $rs;
	}
	public function getCollectAuthorCenterList($p, $uid) {
		$rs = array();

		$model = new Model_Authorcenter();
		$rs = $model->getCollectAuthorCenterList($p, $uid);

		return $rs;
	}

	public function getAuthorCenterInfo($author_center_id,$uid) {
		$rs = array();

		$model = new Model_Authorcenter();
		$rs = $model->getAuthorCenterInfo($author_center_id,$uid);

		return $rs;
	}

	public function getVideoList($author_center_id, $uid,$p,$type) {
		$rs = array();

		$model = new Model_Authorcenter();
		$rs = $model->getVideoList($author_center_id, $uid,$p,$type);

		return $rs;
	}

	public function getContributeVideoList($uid,$p,$sort,$day) {
		$rs = array();

		$model = new Model_Authorcenter();
		$rs = $model->getContributeVideoList($uid,$p,$sort,$day);

		return $rs;
	}

    public function collectAuthorCenter($uid,$author_center_id){
        $rs = array();

        $model = new Model_Authorcenter();
        $rs = $model->collectAuthorCenter($uid,$author_center_id);

        return $rs;
    }

    public function getBusinessData($uid){
        $rs = array();

        $model = new Model_Authorcenter();
        $rs = $model->getBusinessData($uid);

        return $rs;
    }

}
