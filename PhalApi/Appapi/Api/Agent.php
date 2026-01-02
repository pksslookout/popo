<?php
/**
 * 分享
 */
class Api_Agent extends PhalApi_Api {

	public function getRules() {
		return array(
            'getCode' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),

			'checkAgent'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'getAgentBg'=>array(
			),

			'getAgentCode'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'bg_id' => array('name' => 'bg_id', 'type' => 'string', 'require' => true, 'desc' => '背景图ID'),
			),

			'downloadAgentImg'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'bg_id' => array('name' => 'bg_id', 'type' => 'string', 'require' => true, 'desc' => '背景图ID'),
			),
		);
	}
	

	/**
	 * 分享信息
	 * @desc 用于 获取分享信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].code 邀请码
	 * @return string info[0].href 二维码链接
	 * @return string info[0].qr 二维码图片链接
	 * @return string msg 提示信息
	 */
	public function getCode() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $configPub=getConfigPub();
        $href=$configPub['apk_url'];
        $qr=$configPub['apk_url'];

        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            $href = $configPub['ipa_url'];
        }
        $info['href']=$href;
        $info['qr']=get_upload_path($qr);
        $rs['info'][0]=$info;
        return $rs;
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $checkToken = checkToken($uid,$token);
		if($checkToken==700){
			$rs['code']=700;
			$rs['msg']=T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		$domain = new Domain_Agent();
		$info = $domain->getCode($uid);
        
        if(!$info){
            $rs['code']=1001;
			$rs['msg']=T('信息错误');
			return $rs;
        }

        $configpri=getConfigPri();
        $openinstall_switch=$configpri['openinstall_switch'];
        $openinstall_appkey=$configpri['openinstall_appkey'];

        if($openinstall_switch&&$openinstall_appkey!=""){
            $href=get_upload_path("/appapi/agent/downapp?code=".$info['code']);
            $qr=scerweima($href,1,$uid);
        }else{
            $href=get_upload_path('/portal/index/scanqr');
            $qr=scerweima($href);
        }

		$info['href']=$href;
        $info['qr']=get_upload_path($qr);

        $data=[
            'type'=>'9',
            'nums'=>'1',

        ];
        dailyTasks($uid,$data);
        
		$rs['info'][0]=$info;
		return $rs;			
	}


	/**
     * 获取邀请开关、邀请码必填开关、openinstall开关以及用户是否设置了邀请码
     * @desc 用于获取邀请开关、邀请码必填开关、openinstall开关以及用户是否设置了邀请码
     * @return int code 操作码，0表示成功
     * @return array info
     * @return int info[0]. agent_switch 邀请开关 1打开 0关闭
     * @return int info[0]. agent_must 邀请码是否必填 1是 0否
     * @return int info[0]. has_agent 是否已经设置过邀请码 1是 0否
     * @return int info[0]. openinstall_switch openinstall开关 1开 0关
     * @return string msg 提示信息
	 */
	public function checkAgent(){

		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}


		$configpri=getConfigPri();

		$info[0]['agent_switch']=$configpri['agent_switch'];
		$info[0]['agent_must']=$configpri['agent_must'];  //此参数结合用户登录接口返回的isreg,如果agent_must=0时，只有在isreg=1时app端才会弹窗显示邀请码
		$info[0]['has_agent']=(string)checkAgentIsExist($uid);
        $info[0]['openinstall_switch']=$configpri['openinstall_switch'];

		$rs['info']=$info;

		return $rs;
	}


    /**
     * 获取邀请好友背景图
     * @desc 用于 获取邀请好友背景图
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getAgentBg() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $paylist=[];

        $paylist[]=[
            'id'=>'zh_cn_1',
            'bg'=>get_upload_path("images/agent/agent1@2x.png"),
            'thumb'=>get_upload_path("images/agent/bg1@2x.png"),
        ];

        $paylist[]=[
            'id'=>'zh_cn_2',
            'bg'=>get_upload_path("images/agent/agent2@2x.png"),
            'thumb'=>get_upload_path("images/agent/bg2@2x.png"),
        ];

        $paylist[]=[
            'id'=>'zh_cn_3',
            'bg'=>get_upload_path("images/agent/agent3@2x.png"),
            'thumb'=>get_upload_path("images/agent/bg3@2x.png"),
        ];


        $rs['info']=$paylist;
        return $rs;
    }

    /**
     * 获取邀请码
     * @desc 获取邀请码
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getAgentCode(){

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $bg_id=checkNull($this->bg_id);
        if(strpos($bg_id, 'zh_cn') !== false){
            $lang = 'zh_cn';
        }else{
            $lang = 'en';
        }

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Agent();
        $info = $domain->getCode($uid);
        $code = $info['code'];
        if(!$code){
            $code=createCode();
            $ifok=DI()->notorm->agent_code->where('uid=?',$uid)->update(array("code"=>$code));
            if(!$ifok){
                DI()->notorm->agent_code->insert(array('uid'=>$uid,"code"=>$code));
            }

        }
        $href=get_upload_path("/wap/index.html#/?agentCode=".$code.'&lang='.$lang);
//        $uid = 4341244;
        $info['href']=$href;
        $info['code']=$code;
        $info['qr']=get_upload_path('upload/qr/'.$uid.$lang.'.png');
        if(!urlExists($info['qr'])){
            $curlPost['href'] = $href;
            $curlPost['uid'] = $uid;
            $curlPost['lang'] = $lang;
            $curlPost['sign'] = md5($uid."asfasfw312");
            $re = curlPost($curlPost,get_upload_path('/appapi/agent/getCode'));
        }
        $rs['info'][0]=$info;
        return $rs;
    }

    /**
     * 下载邀请图
     * @desc 下载邀请图
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function downloadAgentImg(){

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $bg_id=checkNull($this->bg_id);
        if(strpos($bg_id, 'zh_cn') !== false){
            $lang = 'zh_cn';
        }else{
            $lang = 'en';
        }

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Agent();
        $info = $domain->getCode($uid);
        $code = $info['code'];
        if(!$code){
            $code=createCode();
            $ifok=DI()->notorm->agent_code->where('uid=?',$uid)->update(array("code"=>$code));
            if(!$ifok){
                DI()->notorm->agent_code->insert(array('uid'=>$uid,"code"=>$code));
            }

        }
//        $uid = 4341244;
        $info['code']=$code;
        $qr=get_upload_path('upload/qr/'.$uid.$lang.'.png');
        $outputImage = 'upload/agent/'.$bg_id.'_'.$uid.'.png';
        $info['url']=get_upload_path($outputImage);
        if(!urlExists($info['url'])){
            $curlPost['qr'] = $qr.'?imageView2/2/w/120/h/120';
            $curlPost['bg_id'] = $bg_id;
            $curlPost['code'] = $code;
            $curlPost['outputImage'] = $outputImage;
            $curlPost['sign'] = md5($code."asfasfw312");
            $re = curlPost($curlPost,get_upload_path('/appapi/agent/getDownloadImg'));
        }

        $data=[
            'type'=>'9',
            'nums'=>'1',

        ];
        dailyTasks($uid,$data);
        $rs['info'][0]=$info;
        return $rs;
    }
	

}
