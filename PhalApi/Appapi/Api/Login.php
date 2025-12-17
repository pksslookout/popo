<?php
/**
 * 登录、注册
 */
if (!session_id()) session_start();
class Api_Login extends PhalApi_Api { 
	public function getRules() {
        return array(
			'userLoginReg' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int', 'default'=>'86',  'desc' => '国家代号'),
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'require' => true,  'min' => '6',  'max'=>'30', 'desc' => '账号'),
                'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
                'agent_code' => array('name' => 'agent_code', 'type' => 'string', 'desc' => '邀请码'),
                'type' => array('name' => 'type', 'type' => 'string',  'default'=>'mobile', 'require' => true, 'desc' => 'mobile/email'),
                'source' => array('name' => 'source', 'type' => 'string',  'default'=>'pc', 'desc' => '来源设备'),
            ),
			'userLogin' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int', 'default'=>'86',  'desc' => '国家代号'),
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'require' => true,  'min' => '6',  'max'=>'30', 'desc' => '账号'),
				'user_pass' => array('name' => 'user_pass', 'type' => 'string','require' => true,  'min' => '1',  'max'=>'30', 'desc' => '密码'),
                'type' => array('name' => 'type', 'type' => 'string',  'default'=>'mobile', 'require' => true, 'desc' => 'mobile/email'),

            ),
			'userReg' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86', 'desc' => '国家代号'),
                'user_login' => array('name' => 'user_login', 'type' => 'string','require' => true,  'min' => '6',  'max'=>'30', 'desc' => '账号'),
				'user_pass' => array('name' => 'user_pass', 'type' => 'string','require' => true,  'min' => '1',  'max'=>'30', 'desc' => '密码'),
				'user_pass2' => array('name' => 'user_pass2', 'type' => 'string',  'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '确认密码'),
                'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
                'agent_code' => array('name' => 'agent_code', 'type' => 'string', 'desc' => '邀请码'),
                'source' => array('name' => 'source', 'type' => 'string',  'default'=>'pc', 'desc' => '来源设备'),
                'type' => array('name' => 'type', 'type' => 'string',  'default'=>'mobile', 'require' => true, 'desc' => 'mobile/email'),
            ),

			'userFindPass' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86',  'desc' => '国家代号'),
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'require' => true,  'min' => '6',  'max'=>'30', 'desc' => '账号'),
				'user_pass' => array('name' => 'user_pass', 'type' => 'string', 'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '密码'),
				'user_pass2' => array('name' => 'user_pass2', 'type' => 'string', 'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '确认密码'),
                'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
                'type' => array('name' => 'type', 'type' => 'string',  'default'=>'mobile', 'require' => true, 'desc' => 'mobile/email'),
            ),

			'getCode' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86', 'require' => true,  'desc' => '国家代号'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),

			'getEmailCode' => array(
                'email' => array('name' => 'email', 'type' => 'string','default'=>'', 'require' => true,  'desc' => '邮箱'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),

			'getEmailCodeCurl' => array(
                'email' => array('name' => 'email', 'type' => 'string','default'=>'', 'require' => true,  'desc' => '邮箱'),
                'code' => array('name' => 'code', 'type' => 'string','default'=>'', 'require' => true,  'desc' => '验证码'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),
			
			'getForgetCode' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86', 'require' => true,  'desc' => '国家代号'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),

			'getEmailForgetCode' => array(
                'email' => array('name' => 'email', 'type' => 'string','default'=>'', 'require' => true,  'desc' => '邮箱'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),

            'getUnionid' => array(
				'code' => array('name' => 'code', 'type' => 'string','desc' => '微信code'),
			),

            'logout' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
			),

            'upUserPush'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'pushid' => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光ID'),
            ),

            'getCancelCondition'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            ),

            'cancelAccount'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'time' => array('name' => 'time', 'type' => 'string', 'desc' => '时间戳'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名'),
            ),
        );
	}
	
    /**
     * 会员登陆,注册
     * @desc 用于用户登陆,注册信息
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string info[0].usersign 腾讯IM签名
     * @return string msg 提示信息
     */
    public function userLoginReg() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $country_code=checkNull($this->country_code);
		$user_login=checkNull($this->user_login);
		$source=checkNull($this->source);
		$code=checkNull($this->code);
		$agent_code=checkNull($this->agent_code);
		$type=checkNull($this->type);
        $now=time();

        $email_check=validateEmail($user_login);
        if(!$email_check){
            $rs['code']=1002;
            $rs['msg']=T('邮箱地址无效');
            return $rs;
        }

        $key = 'getEmailCode_'.md5($user_login);
        $get_code = getcaches($key);

        if (!$get_code) {
            $rs['code'] = 1001;
            $rs['msg'] = T('请先获取验证码');
            return $rs;
        }

        $code_key = 'code_time_'.$user_login.'_'.date('Ymd',$now);
        $code_key_data = getcaches($code_key);
        if ($code != $get_code) {
            if(empty($code_key_data)){
                setcaches($code_key,1,24*3600);
            }else{
                setcaches($code_key,$code_key_data+1,24*3600);
                $code_key_data = $code_key_data+1;
            }
            if(!empty($code_key_data)&&$code_key_data >= 5) {
                $rs['code'] = 1002;
                $rs['msg'] = T('验证码错误次数5，明日再试');
                return $rs;
            }
            $rs['code'] = 1002;
            $rs['msg'] = T('验证码错误');
            return $rs;
        }

        if(!empty($code_key_data)&&$code_key_data >= 5) {
            $rs['code'] = 1002;
            $rs['msg'] = T('验证码错误次数5，明日再试');
            return $rs;
        }

        $domain = new Domain_Login();
        $info = $domain->userLoginReg($country_code,$user_login,$source,$type,$agent_code);

		if($info==1002){
			$rs['code'] = 1002;
			//禁用信息
			$baninfo=$domain->getUserban($user_login);
            $rs['info'][0] =$baninfo;
            return $rs;	
		}else if($info==1003){
			$rs['code'] = 1003;
            $rs['msg'] = T('该账号已被禁用');
            return $rs;	
		}else if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = T('该账号已注销');
            return $rs; 
        }else if($info==1007){
			$rs['code'] = 1007;
            $rs['msg'] = T('注册失败，请重试');
            return $rs;
		}else if($info==1011){
			$rs['code'] = 1011;
            $rs['msg'] = T('邀请码不可用');
            return $rs;
		}

        $rs['info'][0] = $info;

        
        return $rs;
    }

    /**
     * 会员登陆 需要密码
     * @desc 用于用户登陆信息
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string info[0].usersign 腾讯IM签名
     * @return string msg 提示信息
     */
    public function userLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $country_code=checkNull($this->country_code);
		$user_login=checkNull($this->user_login);
		$user_pass=checkNull($this->user_pass);
		$type=checkNull($this->type);

        $domain = new Domain_Login();
        $info = $domain->userLogin($country_code,$user_login,$user_pass,$type);

		if($info==1001){
			$rs['code'] = 1001;
            $rs['msg'] = T('账号或密码错误');
            return $rs;
		}else if($info==1002){
			$rs['code'] = 1002;
			//禁用信息
			$baninfo=$domain->getUserban($user_login);
            $rs['info'][0] =$baninfo;
            return $rs;
		}else if($info==1003){
			$rs['code'] = 1003;
            $rs['msg'] = T('该账号已被禁用');
            return $rs;
		}else if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = T('该账号已注销');
            return $rs;
        }

        $rs['info'][0] = $info;

        return $rs;
    }

   /**
     * 会员注册
     * @desc 用于用户注册信息
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function userReg() {

        $rs = array('code' => 0, 'msg' => T('注册成功'), 'info' => array());

        $country_code=checkNull($this->country_code);
		$user_login=checkNull($this->user_login);
		$user_pass=checkNull($this->user_pass);
		$user_pass2=checkNull($this->user_pass2);
		$source=checkNull($this->source);
		$code=checkNull($this->code);
        $agent_code=checkNull($this->agent_code);
        $type=checkNull($this->type);
        $now=time();

        $email_check=validateEmail($user_login);
        if(!$email_check){
            $rs['code']=1002;
            $rs['msg']=T('邮箱地址无效');
            return $rs;
        }

        $key = 'getEmailCode_'.md5($user_login);
        $get_code = getcaches($key);

        if (!$get_code) {
            $rs['code'] = 1001;
            $rs['msg'] = T('请先获取验证码');
            return $rs;
        }

        $code_key = 'code_time_'.$user_login.'_'.date('Ymd',$now);
        $code_key_data = getcaches($code_key);
        if ($code != $get_code) {
            if(empty($code_key_data)){
                setcaches($code_key,1,24*3600);
            }else{
                setcaches($code_key,$code_key_data+1,24*3600);
                $code_key_data = $code_key_data+1;
            }
            if(!empty($code_key_data)&&$code_key_data >= 5) {
                $rs['code'] = 1002;
                $rs['msg'] = T('验证码错误次数5，明日再试');
                return $rs;
            }
            $rs['code'] = 1002;
            $rs['msg'] = T('验证码错误');
            return $rs;
        }

		if($user_pass!=$user_pass2){
            $rs['code'] = 1003;
            $rs['msg'] = T('两次输入的密码不一致');
            return $rs;
		}

		$check = passcheck($user_pass);

		if(!$check){
            $rs['code'] = 1004;
            $rs['msg'] = T('密码为6-20位字母数字组合');
            return $rs;
        }

		$domain = new Domain_Login();
		$info = $domain->userReg($country_code,$user_login,$user_pass,$source,$type,$agent_code);

		if($info==1006){
			$rs['code'] = 1006;
            $rs['msg'] = T('该邮箱已被注册！');
            return $rs;
		}else if($info==1007){
			$rs['code'] = 1007;
            $rs['msg'] = T('注册失败，请重试');
            return $rs;
		}

        $rs['info'][0] = $info;

        return $rs;
    }

	/**
     * 会员找回密码
     * @desc 用于会员找回密码
     * @return int code 操作码，0表示成功，1表示验证码错误，2表示用户密码不一致,3短信手机和登录手机不一致 4、用户不存在 801 密码6-12位数字与字母
     * @return array info 
     * @return string msg 提示信息
     */
    public function userFindPass() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $country_code=checkNull($this->country_code);
		$user_login=checkNull($this->user_login);
		$user_pass=checkNull($this->user_pass);
		$user_pass2=checkNull($this->user_pass2);
		$code=checkNull($this->code);
        $type=checkNull($this->type);
        $now=time();

        $email_check=validateEmail($user_login);
        if(!$email_check){
            $rs['code']=1002;
            $rs['msg']=T('邮箱地址无效');
            return $rs;
        }

        $key = 'getEmailForgetCode_'.md5($user_login);
        $get_code = getcaches($key);

        if (!$get_code) {
            $rs['code'] = 1001;
            $rs['msg'] = T('请先获取验证码');
            return $rs;
        }

        $code_key = 'code_time_'.$user_login.'_'.date('Ymd',$now);
        $code_key_data = getcaches($code_key);
        if ($code != $get_code) {
            if(empty($code_key_data)){
                setcaches($code_key,1,24*3600);
            }else{
                setcaches($code_key,$code_key_data+1,24*3600);
                $code_key_data = $code_key_data+1;
            }
            if(!empty($code_key_data)&&$code_key_data >= 5) {
                $rs['code'] = 1002;
                $rs['msg'] = T('验证码错误次数5，明日再试');
                return $rs;
            }
            $rs['code'] = 1002;
            $rs['msg'] = T('验证码错误');
            return $rs;
        }

		if($user_pass!=$user_pass2){
            $rs['code'] = 1003;
            $rs['msg'] = T('两次输入的密码不一致');
            return $rs;
		}

		$check = passcheck($user_pass);
		if(!$check){
            $rs['code'] = 1004;
            $rs['msg'] = T('密码为6-20位字母数字组合');
            return $rs;
        }

		$domain = new Domain_Login();
        $info = $domain->userFindPass($country_code,$user_login,$user_pass,$type);

		if($info==1006){
			$rs['code'] = 1006;
            $rs['msg'] = T('该帐号不存在');
            return $rs;
		}else if($info===false){
			$rs['code'] = 1007;
            $rs['msg'] = T('重置失败，请重试');
            return $rs;
		}

        return $rs;
    }
	
	/**
	 * 获取登录，注册短信验证码
	 * @desc 用于登录，注册获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function getCode() {
		$rs = array('code' => 0, 'msg' => T('发送成功'), 'info' => array(),"verificationcode"=>0);

        $country_code = checkNull($this->country_code);
		$mobile = checkNull($this->mobile);
//		$sign = checkNull($this->sign);

        $sms_check=$this->checkSmsType($country_code,$mobile);
        if($sms_check['code'] !=0){
            return $sms_check;
        }
        
        $checkdata=array(
//            'country_code'=>$country_code,
            'mobile'=>$mobile
        );
        
//        $issign=checkSign($checkdata,$sign);
//        if(!$issign){
//            $rs['code']=1001;
//			$rs['msg']=T('签名错误');
//			return $rs;
//        }

		if($_SESSION['country_code']==$country_code && $_SESSION['reg_mobile']==$mobile && $_SESSION['reg_mobile_expiretime']> time() ){
			$rs['code']=1002;
			$rs['msg']=T('验证码5分钟有效，请勿多次发送');
			return $rs;
		}
		
        $limit = ip_limit();	
		if( $limit == 1){
			$rs['code']=1003;
			$rs['msg']=T('您已当日发送次数过多');
			return $rs;
		}		
		$mobile_code = random(6,1);
		
		/* 发送验证码 */
 		$result=sendCode($country_code,$mobile,$mobile_code);
		if($result['code']==0){
            $rs['verificationcode']=$mobile_code;
            $_SESSION['country_code'] = $country_code;
			$_SESSION['reg_mobile'] = $mobile;
			$_SESSION['reg_mobile_code'] = $mobile_code;
			$_SESSION['reg_mobile_expiretime'] = time() +60*5;	
		}else if($result['code']==667){
            $_SESSION['country_code'] = $country_code;
			$_SESSION['reg_mobile'] = $mobile;
            $_SESSION['reg_mobile_code'] = $result['msg'];
            $_SESSION['reg_mobile_expiretime'] = time() +60*5;
            
            $rs['verificationcode']='123456';
            $rs['code']=1002;
			$rs['msg']=T('验证码为：').$result['msg'];
		}else{
			$rs['code']=1002;
			$rs['msg']=$result['msg'];
		} 
		
		
		return $rs;
	}

	/**
	 * 获取登录，注册邮箱验证码
	 * @desc 用于登录，注册获取邮箱验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getEmailCode() {
		$rs = array('code' => 0, 'msg' => T('发送成功'), 'info' => array(),"verificationcode"=>0);

        $email = checkNull($this->email);
		$sign = checkNull($this->sign);

        $email_check=validateEmail($email);
        if(!$email_check){
            $rs['code']=1002;
            $rs['msg']=T('邮箱地址无效');
            return $rs;
        }

        $key = 'getEmailCode_'.md5($email);
        $get_code = getcaches($key);

		if($get_code){
			$rs['code']=1002;
			$rs['msg']=T('验证码10分钟有效，请勿多次发送');
			return $rs;
		}

        $limit = ip_limit();
		if( $limit == 1){
			$rs['code']=1003;
			$rs['msg']=T('您已当日发送次数过多');
			return $rs;
		}


        $where="user_email='{$email}'";
        $checkuser = checkUser($where);

        if($checkuser){
            $rs['code']=1004;
            $rs['msg'] = T('该邮箱已被注册！');
            return $rs;
        }

        $code = random(6,1);
        $url = get_upload_path('/appapi/?service=Login.GetEmailCodeCurl');

        $checkdata=array(
            'email'=>$email,
            'code'=>$code,
        );

        $newsign=getSign($checkdata);

        $curlPost = [
            'email'=>$email,
            'code'=>$code,
            'sign'=>$newsign,
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_exec($curl);

        $time = 60*10;
        setcaches($key, $code, $time);

		return $rs;
	}

	/**
	 * 获取登录，注册邮箱验证码
	 * @desc 用于登录，注册获取邮箱验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getEmailCodeCurl() {
		$rs = array('code' => 0, 'msg' => T('发送成功'), 'info' => array(),"verificationcode"=>0);

        $email = checkNull($this->email);
        $code = checkNull($this->code);
		$sign = checkNull($this->sign);

        $email_check=validateEmail($email);
        if(!$email_check){
            $rs['code']=1002;
            $rs['msg']=T('邮箱地址无效');
            return $rs;
        }

        $checkdata=array(
            'email'=>$email,
            'code'=>$code,
        );

        $issign=checkSign($checkdata,$sign);

        if(!$issign){
            $rs['code']=1001;
			$rs['msg']=T('签名错误');
			return $rs;
        }

		/* 发送验证码 */
 		$result=sendEmailCode($email,$code);
		if($result['code']==0){
		}else{
			$rs['code']=1002;
			$rs['msg']=$result['msg'];
		}

		return $rs;
	}

	/**
	 * 获取找回密码短信验证码
	 * @desc 用于找回密码获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return string msg 提示信息
	 */
	 
	public function getForgetCode() {
		$rs = array('code' => 0, 'msg' => T('发送成功'), 'info' => array(),"verificationcode"=>0);

        $country_code = checkNull($this->country_code);
		$mobile = checkNull($this->mobile);
		$sign = checkNull($this->sign);

        $sms_check=$this->checkSmsType($country_code,$mobile);
        if($sms_check['code'] !=0){
            return $sms_check;
        }

        $checkdata=array(
//            'country_code'=>$country_code,
            'mobile'=>$mobile
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
			$rs['msg']=T('签名错误');
			return $rs;
        }

        $where="country_code='{$country_code}' and user_login='{$mobile}'";
        $checkuser = checkUser($where);

        if(!$checkuser){
            $rs['code']=1004;
			$rs['msg']=T('该手机号未注册');
			return $rs;
        }

        //判断手机号是否注销
        $is_destroy=checkIsDestroyByLogin($mobile);
        if($is_destroy){
            $rs['code']=1005;
            $rs['msg']=T('该手机号已注销');
            return $rs;
        }

		if($_SESSION['forget_country_code']==$country_code && $_SESSION['forget_mobile']==$mobile && $_SESSION['forget_mobile_expiretime']> time() ){
			$rs['code']=1002;
			$rs['msg']=T('验证码5分钟有效，请勿多次发送');
			return $rs;
		}

        $limit = ip_limit();
		if( $limit == 1){
			$rs['code']=1003;
			$rs['msg']=T('您已当日发送次数过多');
			return $rs;
		}
		$mobile_code = random(6,1);

		/* 发送验证码 */
 		$result=sendCode($country_code,$mobile,$mobile_code);
		if($result['code']==0){
            $rs['verificationcode']=$mobile_code;
            $_SESSION['forget_country_code'] = $country_code;
			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $mobile_code;
			$_SESSION['forget_mobile_expiretime'] = time() +60*5;
		}else if($result['code']==667){
            $_SESSION['forget_country_code'] = $country_code;
			$_SESSION['forget_mobile'] = $mobile;
            $_SESSION['forget_mobile_code'] = $result['msg'];
            $_SESSION['forget_mobile_expiretime'] = time() +60*5;

            $rs['verificationcode']='123456';
            $rs['code']=1002;
			$rs['msg']=T('验证码为：').$result['msg'];
		}else{
			$rs['code']=1002;
			$rs['msg']=$result['msg'];
		}

		return $rs;
	}

	/**
	 * 获取找回密码邮箱验证码
	 * @desc 用于找回密码获取邮箱验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info
	 * @return string msg 提示信息
	 */

	public function getEmailForgetCode() {
        $rs = array('code' => 0, 'msg' => T('发送成功'), 'info' => array(),"verificationcode"=>0);

        $email = checkNull($this->email);
        $sign = checkNull($this->sign);

        $email_check=validateEmail($email);
        if(!$email_check){
            $rs['code']=1002;
            $rs['msg']=T('邮箱地址无效');
            return $rs;
        }

        $key = 'getEmailForgetCode_'.md5($email);
        $get_code = getcaches($key);

        if($get_code){
            $rs['code']=1002;
            $rs['msg']=T('验证码10分钟有效，请勿多次发送');
            return $rs;
        }

        $limit = ip_limit();
        if( $limit == 1){
            $rs['code']=1003;
            $rs['msg']=T('您已当日发送次数过多');
            return $rs;
        }

        $code = random(6,1);
        $url = get_upload_path('/appapi/?service=Login.GetEmailCodeCurl');

        $checkdata=array(
            'email'=>$email,
            'code'=>$code,
        );

        $newsign=getSign($checkdata);

        $curlPost = [
            'email'=>$email,
            'code'=>$code,
            'sign'=>$newsign,
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_exec($curl);

        $time = 60*10;
        setcaches($key, $code, $time);

        return $rs;
	}
    
	/**
	 * 获取微信登录unionid
	 * @desc 用于获取微信登录unionid
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return string info[0].unionid 微信unionid
	 * @return string msg 提示信息
	 */    
    public function getUnionid(){
        $rs['code'] = 1001;
        $rs['msg'] = T('接口已关闭');
        return $rs;
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $code=checkNull($this->code);
        
        if($code==''){
            $rs['code']=1001;
			$rs['msg']=T('参数错误');
			return $rs;
            
        }

        $configpri=getConfigPri();
    
        $AppID = $configpri['wx_mini_appid'];
        $AppSecret = $configpri['wx_mini_appsecret'];
        /* 获取token */
        //$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$AppID}&secret={$AppSecret}&code={$code}&grant_type=authorization_code";
        $url="https://api.weixin.qq.com/sns/jscode2session?appid={$AppID}&secret={$AppSecret}&js_code={$code}&grant_type=authorization_code";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $json =  curl_exec($ch);
        curl_close($ch);
        $arr=json_decode($json,1);
        //file_put_contents('./getUnionid.txt',date('Y-m-d H:i:s').' 提交参数信息 code:'.json_encode($code)."\r\n",FILE_APPEND);
        //file_put_contents('./getUnionid.txt',date('Y-m-d H:i:s').' 提交参数信息 arr:'.json_encode($arr)."\r\n",FILE_APPEND);
        if($arr['errcode']){
            $rs['code']=1003;
			$rs['msg']=T('配置错误');
            //file_put_contents('./getUnionid.txt',date('Y-m-d H:i:s').' 提交参数信息 arr:'.json_encode($arr)."\r\n",FILE_APPEND);
			return $rs;
        }
        
        

        /* 小程序 绑定到 开放平台 才有 unionid  否则 用 openid  */
        $unionid=$arr['unionid'];

        if(!$unionid){
            //$rs['code']=1002;
			//$rs['msg']='公众号未绑定到开放平台';
			//return $rs;
            
            $unionid=$arr['openid'];
        }
        
        $rs['info'][0]['unionid'] = $unionid;
        $rs['info'][0]['openid'] = $arr['openid'];
        return $rs;
    }
    
	/**
	 * 退出
	 * @desc 用于用户退出 注销极光
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function logout() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = checkNull($this->uid);
		$token=checkNull($this->token);
        
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        

		$info = userLogout($uid);


		return $rs;			
	}


    /**
     * 更新极光pushid
     * @desc 用于更新极光pushid
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function upUserPush(){

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $pushid=checkNull($this->pushid);

        $domain=new Domain_Login();
        $domain->upUserPush($uid,$pushid);

        return $rs;
        
    }

    /**
     * 获取注销账号的条件
     * @desc 用于获取注销账号的条件
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info[0]['list'] 条件数组
     * @return string info[0]['list'][]['title'] 标题
     * @return string info[0]['list'][]['content'] 内容
     * @return string info[0]['list'][]['is_ok'] 是否满足条件 0 否 1 是
     * @return string info[0]['can_cancel'] 是否可以注销账号 0 否 1 是
     */
    public function getCancelCondition(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain=new Domain_Login();
        $res=$domain->getCancelCondition($uid);

        $rs['info'][0]=$res;

        return $rs;
    }

    /**
     * 用户注销账号
     * @desc 用于用户注销账号
     * @return int code 状态码,0表示成功
     * @return string msg 返回提示信息
     * @return array info 返回信息
     */
    public function cancelAccount(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $time=checkNull($this->time);
        $sign=checkNull($this->sign);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if(!$time||!$sign){
            $rs['code'] = 1001;
            $rs['msg'] = T('参数错误');
            return $rs;
        }

        $now=time();
        if($now-$time>300){
            $rs['code']=1001;
            $rs['msg']=T('参数错误');
            return $rs;
        }

        
        $checkdata=array(
            'uid'=>$uid,
            'token'=>$token,
            'time'=>$time
        );
        
        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']=T('签名错误');
            return $rs; 
        }

        $domain=new Domain_Login();
        $res=$domain->cancelAccount($uid);

        if($res==1001){
        	$rs['code']=1001;
            $rs['msg']=T('相关内容不符合注销账号条件');
            return $rs;
        }

        $rs['msg']=T('注销成功,手机号、身份证号等信息已解除');
        return $rs;
    }

    /**
     * 检测短信开关
     */
    private function checkSmsType($country_code,$mobile){
        $rs=array('code'=>0,'msg'=>'','info'=>array());

        $configpri=getConfigPri();
        $typecode_switch=$configpri['typecode_switch'];

        if($typecode_switch==1){ //阿里云验证码

            $aly_sendcode_type=$configpri['aly_sendcode_type'];

            if($aly_sendcode_type==1){ //国内验证码
                if($country_code!=86){
                    $rs['code']=1001;
                    $rs['msg']=T('平台只允许选择中国大陆');
                    return $rs;
                }

                $ismobile=checkMobile($mobile);
                if(!$ismobile){
                    $rs['code']=1001;
                    $rs['msg']=T('请输入正确的手机号');
                    return $rs;
                }

            }else if($aly_sendcode_type==2){ //海外/港澳台 验证码
                if($country_code==86){
                    $rs['code']=1001;
                    $rs['msg']=T('平台只允许选择除中国大陆外的国家/地区');
                    return $rs;
                }
            }
        }else if($typecode_switch==2){ //容联云

            $ismobile=checkMobile($mobile);
            if(!$ismobile){
                $rs['code']=1001;
                $rs['msg']=T('请输入正确的手机号');
                return $rs;
            }
        }else if($typecode_switch==3){ //腾讯云

            $tencent_sendcode_type=$configpri['tencent_sendcode_type'];
            if($tencent_sendcode_type==1){ //中国大陆
                if($country_code!=86){
                    $rs['code']=1001;
                    $rs['msg']=T('平台只允许选择中国大陆');
                    return $rs;
                }

                $ismobile=checkMobile($mobile);
                if(!$ismobile){
                    $rs['code']=1001;
                    $rs['msg']=T('请输入正确的手机号');
                    return $rs;
                }
            }else if($tencent_sendcode_type==2){ //海外/港澳台 验证码
                if($country_code==86){
                    $rs['code']=1001;
                    $rs['msg']=T('平台只允许选择除中国大陆外的国家/地区');
                    return $rs;
                }
            }
        }

        return $rs;
    }

    

}
