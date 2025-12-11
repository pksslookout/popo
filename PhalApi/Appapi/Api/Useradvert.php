<?php
/**
 * 用户广告
 */
class Api_Useradvert extends PhalApi_Api {

	public function getRules() {
		return array(

            'getAdvertiserInfo'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),
			'applyAdvertiser'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'certification_entity' => array('name' => 'certification_entity', 'type' => 'string', 'require' => true, 'desc' => '认证主体'),
                'phone' => array('name' => 'phone', 'type' => 'string', 'require' => true, 'desc' => '电话'),
                'certification_explain' => array('name' => 'certification_explain', 'type' => 'string', 'require' => true, 'desc' => '认证说明'),
                'qualification_picture_one' => array('name' => 'qualification_picture_one', 'type' => 'string', 'require' => true, 'desc' => '资质图片1'),
                'qualification_picture_two' => array('name' => 'qualification_picture_two', 'type' => 'string', 'require' => true, 'desc' => '资质图片2'),
			),

		);
	}
	

	/**
	 * 获取广告主信息
	 * @desc 用于 获取广告主信息
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getAdvertiserInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

		$domain = new Domain_Useradvert();
        $res = $domain->getAdvertiserInfo($uid);
        if(empty($res)){
            $rs['code']=1001;
            $rs['msg']=T('请先进行广告主认证');
            return $rs;
        }
        $res['qualification_picture_one'] = get_upload_path($res['qualification_picture_one']);
        $res['qualification_picture_two'] = get_upload_path($res['qualification_picture_two']);
        if($res['is_status']==0){
            $rs['code']=1003;
            $rs['msg']=T('资料提交成功，审核中');
            return $rs;
        }
        if($res['is_status']==1){
            $rs['code']=1002;
            $rs['msg']=T('您的认证没有通过，请重新认证，原因：').$res['reason'];
            $rs['info'][0]=$res;
            return $rs;
        }

        $rs['info'][0]=$res;
        return $rs;

		return $rs;
	}

    /**
     * 申请广告主
     * @desc 用于 申请广告主
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function applyAdvertiser() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $certification_entity=checkNull($this->certification_entity);
        $phone=checkNull($this->phone);
        $certification_explain=checkNull($this->certification_explain);
        $qualification_picture_one=checkNull($this->qualification_picture_one);
        $qualification_picture_two=checkNull($this->qualification_picture_two);

        $data=array(
            'uid'=>$uid,
            'certification_entity'=>$certification_entity,
            'phone'=>$phone,
            'certification_explain'=>$certification_explain,
            'qualification_picture_one'=>$qualification_picture_one,
            'qualification_picture_two'=>$qualification_picture_two,
        );

        $domain = new Domain_Useradvert();
        $rs['info'] = $domain->applyAdvertiser($data);

        if(!$rs['info']){
            $rs['code']=1001;
            $rs['msg']='信息错误';
            return $rs;
        }
        return $rs;
    }


}
