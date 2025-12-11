<?php

class Domain_Official {
	public function getOfficialList($p) {

		$model = new Model_Official();
		$rs = $model->getOfficialList($p);

		return $rs;
	}
	public function getOfficialNews() {

		$model = new Model_Official();
		$rs = $model->getOfficialNews();

		return $rs;
	}

	public function getOfficialInfo($p) {

		$model = new Model_Official();
		$rs = $model->getOfficialInfo($p);

		return $rs;
	}

}
