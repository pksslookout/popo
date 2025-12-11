<?php
/**
 * 消息
 */
class Api_Newmessage extends PhalApi_Api {

	public function getRules() {
		return array(
			'getAllNewMessage' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
			'clearFansCount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
			'clearLikeCount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
			'clearAtCount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
			'clearCommentCount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
			'clearSystemCount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
			'clearAllCount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
			),
            'getNews' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            ),
		);
	}
	
	/**
	 * 获取系统所有最新消息数
	 * @desc 用于 获取系统所有最新消息数
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function getAllNewMessage() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		$domain = new Domain_Newmessage();
		$info = $domain->getAllNewMessage($uid);

		$rs['info']=$info;
		return $rs;			
	}

	/**
	 * 清除粉丝数
	 * @desc 用于 清除粉丝数
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function clearFansCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_Newmessage();
		$info = $domain->clearFansCount($uid);

		$rs['info']=$info;
		return $rs;
	}

	/**
	 * 清除赞数
	 * @desc 用于 清除赞数
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function clearLikeCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_Newmessage();
		$info = $domain->clearLikeCount($uid);

		$rs['info']=$info;
		return $rs;
	}

	/**
	 * 清除@我的数
	 * @desc 用于 清除@我的数
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function clearAtCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_Newmessage();
		$info = $domain->clearAtCount($uid);

		$rs['info']=$info;
		return $rs;
	}

	/**
	 * 清除评论数
	 * @desc 用于 清除评论数
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function clearCommentCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_Newmessage();
		$info = $domain->clearCommentCount($uid);

		$rs['info']=$info;
		return $rs;
	}

	/**
	 * 清除系统通知数
	 * @desc 用于 清除系统通知数
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function clearSystemCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_Newmessage();
		$info = $domain->clearSystemCount($uid);

		$rs['info']=$info;
		return $rs;
	}

	/**
	 * 全部已读（全部清除）
	 * @desc 用于 全部已读
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function clearAllCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_Newmessage();
		$info = $domain->clearAllCount($uid);

		$rs['info']=$info;
		return $rs;
	}
    /**
     * 获取系统最新消息与最新官方通知
     * @desc 用于 获取系统最新消息与最新官方通知
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0] 支付信息
     * @return string msg 提示信息
     */
    public function getNews() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Message();
        $info = $domain->getNews($uid);

        $info['addtime']=date('m-d H:i',$info['addtime']);
        $info['thumb']=get_upload_path('');

        $rs['info']['system']=$info;

        $domain = new Domain_Official();
        $info = $domain->getOfficialNews();

        $info['pushtime']=date('m-d H:i',$info['pushtime']);
        $info['thumb']=get_upload_path('');

        $rs['info']['official']=$info;
        return $rs;
    }
	
}
