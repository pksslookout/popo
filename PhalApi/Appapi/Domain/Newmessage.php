<?php

class Domain_Newmessage {
	public function getAllNewMessage($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->getAllNewMessage($uid);
        return $rs;
    }
	public function clearFansCount($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->clearFansCount($uid);
        return $rs;
    }
	public function clearLikeCount($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->clearLikeCount($uid);
        return $rs;
    }
	public function clearAtCount($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->clearAtCount($uid);
        return $rs;
    }
	public function clearCommentCount($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->clearCommentCount($uid);
        return $rs;
    }
	public function clearSystemCount($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->clearSystemCount($uid);
        return $rs;
    }
	public function clearAllCount($uid)
    {
        $model = new Model_Newmessage();
        $rs = $model->clearAllCount($uid);
        return $rs;
    }
}
