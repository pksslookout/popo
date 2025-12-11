<?php

class Domain_Advert {
	public function getAdvertList($p) {
		$rs = array();

		$model = new Model_Advert();
		$rs = $model->getAdvertList($p);

		return $rs;
	}

	public function getAdvertCommentList($p, $advertid) {
		$rs = array();

		$model = new Model_Advert();
		$rs = $model->getAdvertCommentList($p, $advertid);

		return $rs;
	}

	public function advertComment($data) {
		$rs = array();

		$model = new Model_Advert();
		$rs = $model->advertComment($data);

		return $rs;
	}

	public function likeAdvertComment($data) {
		$rs = array();

		$model = new Model_Advert();
		$rs = $model->likeAdvertComment($data);

		return $rs;
	}

	public function likeAdvert($data) {
		$rs = array();

		$model = new Model_Advert();
		$rs = $model->likeAdvert($data);

		return $rs;
	}

	public function shareAdvert($data) {
		$rs = array();

		$model = new Model_Advert();
		$rs = $model->shareAdvert($data);

		return $rs;
	}
	
}
