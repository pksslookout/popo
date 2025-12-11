<?php

class Domain_Team {

    public function getMyInfo($uid) {
        $rs = array();

        $model = new Model_Team();
        $rs = $model->getMyInfo($uid);

        return $rs;
    }

   	public function getMyTeamLists($uid,$p,$isauth,$sort,$key){

        $rs = array();

        $model = new Model_Team();
        $rs = $model->getMyTeamLists($uid,$p,$isauth,$sort,$key);

        return $rs;
    }

}
