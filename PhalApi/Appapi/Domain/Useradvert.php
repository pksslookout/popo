<?php

class Domain_Useradvert {
	public function getAdvertList($p) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->getAdvertList($p);

		return $rs;
	}

	public function getAdvertiserInfo($uid) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->getAdvertiserInfo($uid);

		return $rs;
	}

	public function getAdvertCommentList($p, $advertid) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->getAdvertCommentList($p, $advertid);

		return $rs;
	}

	public function advertComment($data) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->advertComment($data);

		return $rs;
	}

	public function likeAdvertComment($data) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->likeAdvertComment($data);

		return $rs;
	}

	public function likeAdvert($data) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->likeAdvert($data);

		return $rs;
	}

	public function shareAdvert($data) {
		$rs = array();

		$model = new Model_Useradvert();
		$rs = $model->shareAdvert($data);

		return $rs;
	}

    public function applyAdvertiser($data) {
        $rs = array();

        $model = new Model_Useradvert();
        $rs = $model->applyAdvertiser($data);

        return $rs;
    }

    public function addUserAdvert($data) {
        $rs = array();

        $model = new Model_Useradvert();
        $rs = $model->addUserAdvert($data);

        return $rs;
    }
	
}
