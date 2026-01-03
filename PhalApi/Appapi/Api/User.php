<?php
/**
 * 用户信息
 */
//if (!session_id()) session_start();
class Api_User extends PhalApi_Api {

	public function getRules() {
		return array(
			'iftoken' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'getBaseInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),
            
            'getBaseInfoCount'=> array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),

            'getUserLevel'=> array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'require' => false, 'desc' => '其他用户ID（优选读取）'),
			),
			
			'updateAvatar' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                /*'file' => array('name' => 'file','type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 30, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),*/
				'avatar' => array('name' => 'avatar','type' => 'string', 'require' => true, 'desc' => '用户头像地址'),
			),
			
			'updateFields' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'fields' => array('name' => 'fields', 'type' => 'string', 'require' => true, 'desc' => '修改信息，json字符串'),
			),
			
			'updatePass' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'old_pass' => array('name' => 'old_pass', 'type' => 'string', 'require' => true, 'desc' => '旧密码'),
				'pass' => array('name' => 'pass', 'type' => 'string', 'require' => true, 'desc' => '新密码'),
				'pass2' => array('name' => 'pass2', 'type' => 'string', 'require' => true, 'desc' => '确认密码'),
			),

			'updateBnbAdr' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'bnb_adr' => array('name' => 'pass', 'type' => 'string', 'require' => true, 'desc' => 'BNB地址'),
			),

			'updatePayPass' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'pay_pass' => array('name' => 'pass', 'type' => 'string', 'require' => true, 'desc' => '新密码'),
				'pay_pass2' => array('name' => 'pass2', 'type' => 'string', 'require' => true, 'desc' => '确认密码'),
			),
			
			'getBalance' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'desc' => '设备类型，0android，1IOS'),
                'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),

			'getMyUsdtInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'getChain' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'getChainDetail' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'id' => array('name' => 'id', 'type' => 'string', 'require' => true, 'desc' => '链类型ID'),
//                'source' => array('name' => 'source', 'type' => 'string', 'require' => false, 'default'=>'coin', 'desc' => 'coin 钻石，balance 商城余额'),
            ),

			'checkChainOrder' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'adr' => array('name' => 'adr', 'type' => 'string', 'require' => true, 'desc' => '充值地址'),
            ),

			'forwardChainUsdt' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'adr' => array('name' => 'adr', 'type' => 'string', 'require' => true, 'desc' => '充值地址'),
                'chainType' => array('name' => 'chainType', 'type' => 'string', 'require' => true, 'desc' => '主网络'),
                'number' => array('name' => 'number', 'type' => 'float', 'min' => 1, 'require' => true, 'desc' => '充值数量'),
                'user_pay_pass' => array('name' => 'user_pay_pass', 'type' => 'string', 'require' => true, 'desc' => '支付密码'),
                'timestamp' => array('name' => 'timestamp', 'type' => 'string', 'require' => true, 'desc' => '秒级时间戳'),
                'nonce' => array('name' => 'nonce', 'type' => 'string', 'require' => true, 'desc' => '8位随机数（包含字母数字）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'require' => true, 'default'=>'', 'desc' => '签名(uid+adr+number+timestamp+nonce)'),
            ),

			'getAccountType' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'desc' => '设备类型，0android，1IOS'),
                'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),

			'getVipCharge' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'desc' => '设备类型，0android，1IOS'),
                'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),

			'setVipBalance' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'rules_id' => array('name' => 'rules_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '规则ID'),
			),
			
			'getProfit' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'getUsdtForward' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'getRedProfit' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'setCash' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'accountid' => array('name' => 'accountid', 'type' => 'int', 'require' => true, 'desc' => '账号ID'),
				'cashvote' => array('name' => 'cashvote', 'type' => 'int', 'require' => true, 'desc' => '提现的票数'),
			),
			
			'setAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'isAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'isBlacked' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			'checkBlack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),

			'setBlack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'getBindCode' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86', 'require' => true,  'desc' => '国家代号'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),
			
			'setMobile' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86', 'require' => true,  'desc' => '国家代号'),
//				'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
			),
			
			'getFollowsList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
                'user_nicename' => array('name' => 'user_nicename', 'type' => 'string', 'min' => 1, 'desc' => '用户名称搜索'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),

			'getPopularVideoList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'status' => array('name' => 'status', 'type' => 'int', 'require' => true, 'desc' => '0 未完成 1 已完成'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),

			'getPopularLiveList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'status' => array('name' => 'status', 'type' => 'int', 'require' => true, 'desc' => '0 未完成 1 已完成'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),

			'getLikesList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),

			'getCommentsList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),

            'getAtsList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
			
			'getFansList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'status' => array('name' => 'status', 'type' => 'int', 'desc' => '是否关注'),
				'keyword' => array('name' => 'keyword', 'type' => 'string', 'desc' => '关键字'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getBlackList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getLiverecord' => array(
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getAliCdnRecord' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '直播记录ID'),
            ),
			
			'getUserHome' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => false, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'getContributeList' => array(
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
			
			'getPmUserInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'getMultiInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'uids' => array('name' => 'uids', 'type' => 'string', 'min' => 1,'require' => true, 'desc' => '用户ID，多个以逗号分割'),
				'type' => array('name' => 'type', 'type' => 'int', 'require' => true, 'desc' => '关注类型，0 未关注 1 已关注'),
			),
            
            'getUidsInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'uids' => array('name' => 'uids', 'type' => 'string', 'min' => 1,'require' => true, 'desc' => '用户ID，多个以逗号分割'),
			),

			'Bonus' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

            'getBonus' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'setDistribut' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'code' => array('name' => 'code', 'type' => 'string', 'require' => true, 'desc' => '邀请码'),
			),

			'getUserLabel' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
            
            'setUserLabel' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
                'labels' => array('name' => 'labels', 'type' => 'string', 'require' => true, 'desc' => '印象标签ID，多个以逗号分割'),
			),

            'getMyLabel' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

            'getUserAccountList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

            'setUserAccount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'int', 'require' => true, 'desc' => '账号类型，1表示支付宝，2表示微信，3表示银行卡'),
                'account_bank' => array('name' => 'account_bank', 'type' => 'string', 'default' => '', 'desc' => '银行名称'),
                'account' => array('name' => 'account', 'type' => 'string', 'require' => true, 'desc' => '账号'),
                'name' => array('name' => 'name', 'type' => 'string', 'default' => '', 'desc' => '姓名'),
			),
            
            'delUserAccount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'desc' => '账号ID'),
			),

			'setShopCash' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'accountid' => array('name' => 'accountid', 'type' => 'int', 'require' => true, 'desc' => '账号ID'),
				'money' => array('name' => 'money', 'type' => 'float', 'require' => true, 'desc' => '提现的金额'),
				'time' => array('name' => 'time', 'type' => 'string', 'desc' => '时间戳'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名字符串'),
			),

			'getAuthInfo'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'setAuthInfo'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'real_name' => array('name' => 'real_name', 'type' => 'string', 'require' => true, 'desc' => '真实姓名'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'require' => true, 'desc' => '手机号码'),
				'cer_no' => array('name' => 'cer_no', 'type' => 'string', 'require' => true, 'desc' => '身份证号'),
				'front_view' => array('name' => 'front_view', 'type' => 'string', 'require' => true, 'desc' => '证件正面'),
				'back_view' => array('name' => 'back_view', 'type' => 'string', 'require' => true, 'desc' => '证件反面'),
				'handset_view' => array('name' => 'handset_view', 'type' => 'string', 'require' => true, 'desc' => '手持证件正面照'),
			),
			
			'seeDailyTasks'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'default' => '0', 'desc' => '主播ID'),
				'islive' => array('name' => 'islive', 'type' => 'int', 'default' => '0',  'desc' => '是否在直播间 0不在 1在'),
				'type' => array('name' => 'type', 'type' => 'string', 'default' => 'day',  'desc' => 'one,day,share'),
			),

			'receiveTaskReward'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'taskid' => array('name' => 'taskid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '任务ID'),
			),

            'getUserVip' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getAgent' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getReportUserClassify' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'report' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
                'content' => array('name' => 'content', 'type' => 'string', 'require' => true, 'desc' => '内容'),
                'reason' => array('name' => 'reason', 'type' => 'string', 'require' => true, 'desc' => '理由'),
                'image' => array('name' => 'image', 'type' => 'string', 'require' => true, 'desc' => '图片'),
                'classifyid' => array('name' => 'classifyid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '类别ID'),
            ),

            'switchLang' => array(
                'lang' => array('name' => 'lang', 'type' => 'string', 'require' => true, 'desc' => '语言 zh_cn 中文 en 英文 vi 越南语 ja 日语 ko 韩语 ar 阿拉伯语 ms 马来语 zh_TW 繁体中文'),
            ),

            'getLangList' => array(
            ),

            'getVideoView' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'delVideoView' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'ids' => array('name' => 'ids', 'type' => 'string', 'default'=>'1,2' ,'desc' => '观看记录ID（多个,号隔开）'),
            ),

            'checkTeenager'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'setTeenagerPassword'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'password'=>array('name' => 'password', 'type' => 'string', 'require' => true, 'desc' => '青少年模式密码'),
                'type'=>array('name' => 'type', 'type' => 'int', 'require' => true, 'desc' => '操作类型 0 设置密码 1开启青少年模式'),
            ),

            'updateTeenagerPassword'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'oldpassword'=>array('name' => 'oldpassword', 'type' => 'string', 'require' => true, 'desc' => '青少年模式旧密码'),
                'password'=>array('name' => 'password', 'type' => 'string', 'require' => true, 'desc' => '青少年模式新密码'),
            ),

            'closeTeenager'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'password'=>array('name' => 'password', 'type' => 'string', 'require' => true, 'desc' => '青少年模式密码'),
            ),

            'addTeenagerTime'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'updateBgImg' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'img' => array('name' => 'img','type' => 'string','require' => true, 'desc' => '背景图' ),
            ),

            'getChangeUserList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
                'source' => array('name' => 'source', 'type' => 'string', 'default'=>'coin' ,'desc' => 'coin 钻石，balance 商城余额'),
            ),

            'getChangeUserUsdtList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getEarningsList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getCashList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getUsdtList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getConversionInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'setConversion' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'conversion_source' => array('name' => 'conversion_source', 'type' => 'string', 'require' => true, 'desc' => 'popo,usdt,coin,lala'),
                'conversion_location' => array('name' => 'conversion_location', 'type' => 'string', 'require' => true, 'desc' => 'popo,usdt,coin,lala'),
                'number' => array('name' => 'number', 'type' => 'float', 'require' => true, 'desc' => '兑换数量'),
                'timestamp' => array('name' => 'timestamp', 'type' => 'string', 'require' => true, 'desc' => '秒级时间戳'),
                'nonce' => array('name' => 'nonce', 'type' => 'string', 'require' => true, 'desc' => '8位随机数（包含字母数字）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'require' => true, 'default'=>'', 'desc' => '签名(uid+conversion_source+conversion_location+number+timestamp+nonce)'),
            ),

            'getConversionList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'require' => true, 'desc' => 'popo,usdt,coin,lala'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getMineMachineInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getPopoInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getMineMachineList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getMyMineMachineList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getMyMineMachineDividend' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getPoPoDividendList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getLalaList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getMyMineMachineRewardList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getMyCoinRewardList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'setTransferPoPoDividendToCurrency' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'number' => array('name' => 'number', 'type' => 'float', 'require' => true, 'desc' => '划转数量'),
                'timestamp' => array('name' => 'timestamp', 'type' => 'string', 'require' => true, 'desc' => '秒级时间戳'),
                'nonce' => array('name' => 'nonce', 'type' => 'string', 'require' => true, 'desc' => '8位随机数（包含字母数字）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'require' => true, 'default'=>'', 'desc' => '签名(uid+number+timestamp+nonce)'),
            ),

            'getScoreInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getScoreEarningsInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'GetMyCooperation' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getScoreList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getDigitalAssetList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'require' => false, 'desc' => 'type CNY,USDT,POPO'),
            ),

            'getDigitalAssetTypeList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'getCoinConversionList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'require' => true, 'desc' => 'usdt,popo,lala'),
            ),

		);
	}

    /**
     * 获取钻石兑换比例数据
     * @desc 用于获取钻石兑换比例数据
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function getCoinConversionList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $type=checkNull($this->type);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        $info = [];
        if($type=='usdt'){
            $info = [
                [
                    'name' => '1000钻石',
                    'to_name' => '1USDT',
                    'value' => 1,
                ],
                [
                    'name' => '6000钻石',
                    'to_name' => '6USDT',
                    'value' => 6,
                ],
                [
                    'name' => '30000钻石',
                    'to_name' => '30USDT',
                    'value' => 30,
                ],
                [
                    'name' => '100000钻石',
                    'to_name' => '100USDT',
                    'value' => 100,
                ],
                [
                    'name' => '300000钻石',
                    'to_name' => '300USDT',
                    'value' => 300,
                ],
                [
                    'name' => '1000000钻石',
                    'to_name' => '1000USDT',
                    'value' => 1000,
                ]
            ];
        }
        if($type=='popo'){
            $info = [
                [
                    'name' => '1000钻石',
                    'to_name' => '1000POPO',
                    'value' => 1000,
                ],
                [
                    'name' => '6000钻石',
                    'to_name' => '6000POPO',
                    'value' => 6000,
                ],
                [
                    'name' => '30000钻石',
                    'to_name' => '30000POPO',
                    'value' => 30000,
                ],
                [
                    'name' => '100000钻石',
                    'to_name' => '100000POPO',
                    'value' => 100000,
                ],
                [
                    'name' => '300000钻石',
                    'to_name' => '300000POPO',
                    'value' => 300000,
                ],
                [
                    'name' => '1000000钻石',
                    'to_name' => '1000000POPO',
                    'value' => 1000000,
                ]
            ];
        }
        if($type=='lala'){
            $info = [
                [
                    'name' => '1000钻石',
                    'to_name' => '1000LALA',
                    'value' => 1000,
                ],
                [
                    'name' => '6000钻石',
                    'to_name' => '6000LALA',
                    'value' => 6000,
                ],
                [
                    'name' => '30000钻石',
                    'to_name' => '30000LALA',
                    'value' => 30000,
                ],
                [
                    'name' => '100000钻石',
                    'to_name' => '100000LALA',
                    'value' => 100000,
                ],
                [
                    'name' => '300000钻石',
                    'to_name' => '300000LALA',
                    'value' => 300000,
                ],
                [
                    'name' => '1000000钻石',
                    'to_name' => '1000000LALA',
                    'value' => 1000000,
                ]
            ];
        }
        $rs['code'] = 0;
        $rs['msg'] = T('获取成功');
        $rs['info'] = $info;
		return $rs;
	}

    /**
     * 获取数字资产
     * @desc 用于获取数字资产
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function getDigitalAssetList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        $type=checkNull($this->type);
        $uid=checkNull($this->uid);

		$checkToken=checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        if(empty($type)){
            $type = 'CNY';
        }

        $rate = 1;

        $exchange_rate=DI()->notorm->exchange_rate
            ->select('value')
            ->where('type = ?', $type)
            ->fetchOne();
        if($exchange_rate){
            $rate = $exchange_rate['value'];
        }

        $property=DI()->notorm->user
            ->select('coin,votes,popo,usdt,score,warehouse_score,blocked_score,locked_score')
            ->where('id = ?', $uid)
            ->fetchOne();
        $score_q = $property['warehouse_score'] + $property['score'] + $property['blocked_score'] + $property['locked_score'];
        $property_value = ($property['coin'] + $property['votes'] + $property['popo'])/1000 + ($score_q/100) + $property['usdt'];
        $property_value = $rate*$property_value;
        $info['property'] = dealPrice($property_value);

        $usdt = $property['usdt'] * $rate;
        $coin = $property['coin'] * $rate / 1000;
        $votes = $property['votes'] * $rate / 1000;
        $popo = $property['popo'] * $rate / 1000;
        $score = $score_q * $rate / 100;

        if($type == 'POPO'){
            $rmb = '≈POPO';
        }else if($type == 'USDT'){
            $rmb = '≈$';
        }else{
            $rmb = '≈¥';
        }

        $info['list'] = [
            [
                'img' => get_upload_path('images/digital/USDT@2x.png'),
                'name' => 'USDT',
                'Des' => 'TetherUS',
                'value' => dealPrice($property['usdt']),
                'rmb' => $rmb.dealPrice($usdt),
            ],
            [
                'img' => get_upload_path('images/digital/COIN@2x.png'),
                'name' => '钻石',
                'Des' => 'Diamond',
                'value' => dealPrice($property['coin']),
                'rmb' => $rmb.dealPrice($coin),
            ],
            [
                'img' => get_upload_path('images/digital/LALA@2x.png'),
                'name' => 'LALA',
                'Des' => 'Lala Points',
                'value' => dealPrice($property['votes']),
                'rmb' => $rmb.dealPrice($votes),
            ],
            [
                'img' => get_upload_path('images/digital/POPO@2x.png'),
                'name' => 'POPO',
                'Des' => 'Mining Points',
                'value' => dealPrice($property['popo']),
                'rmb' => $rmb.dealPrice($popo),
            ],
            [
                'img' => get_upload_path('images/digital/score@2x.png'),
                'name' => '万能积分',
                'Des' => 'Universal Points',
                'value' => dealPrice($score_q),
                'rmb' => $rmb.dealPrice($score),
            ],
        ];
        $rs['code'] = 0;
        $rs['msg'] = T('获取成功');
        $rs['info'] = $info;
		return $rs;
	}

    /**
     * 获取数字资产类型获取
     * @desc 用于获取数字资产类型获取
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function getDigitalAssetTypeList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$checkToken=checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $key = 'getDigitalAssetTypeList';
        $info=getcaches($key);
        if(!$info) {
            $exchange_rate = DI()->notorm->exchange_rate->fetchAll();
            $info = [];
            foreach ($exchange_rate as $k => $v) {
                $info[] = [
                    'name' => $v['type'],
                    'value' => dealPrice($v['value']),
                ];

            }
            if($info){
                setcaches($key,$info);
            }
        }
        $rs['code'] = 0;
        $rs['msg'] = T('获取成功');
        $rs['info'] = $info;
		return $rs;
	}

    /**
     * 获取用户等级数据
     * @desc 获取用户等级数据
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function getUserLevel() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $touid=checkNull($this->touid);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        $domain = new Domain_User();
        if($touid){
            $uid = $touid;
        }
        $info = $domain->getUserLevel($uid);
        if(!$info){
            $rs['code'] = 700;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $rs['info'][0] = $info;

        return $rs;
	}

    /**
     * 判断token
     * @desc 用于判断token
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function iftoken() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$checkToken=checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		return $rs;
	}

    /**
     * 获取我的头像
     * @desc 用于获取我的头像
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function getAvatar() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$checkToken=checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        $list = [
            [

            ],
        ];

		return $rs;
	}

    public function getBaseInfoCount() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getBaseInfoCount($uid);
        if(!$info){
            $rs['code'] = 700;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
        }
    $rs['info'][0] = $info;

		return $rs;
	}
    
	/**
	 * 获取用户信息
	 * @desc 用于获取单个用户基本信息
	 * @return int code 操作码，0表示成功， 1表示用户不存在
	 * @return array info 
	 * @return array info[0] 用户信息
	 * @return int info[0].id 用户ID
	 * @return string info[0].level 等级
	 * @return string info[0].lives 直播数量
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].likes 点赞数 （新增 2025/6/16）
	 * @return string info[0].agent_switch 分销开关
	 * @return string info[0].family_switch 家族开关
	 * @return string msg 提示信息
	 */
	public function getBaseInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getBaseInfo($uid);
        if(!$info){
            $rs['code'] = 700;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
        }
		
		$configpri=getConfigPri();

		$configpub=getConfigPub();
		$agent_switch=$configpri['agent_switch'];
		$family_switch=$configpri['family_switch'];
		$service_switch=$configpri['service_switch'];
		$service_url=$configpri['service_url'];
		$ios_shelves=$configpub['ios_shelves'];
		
		$info['agent_switch']=$agent_switch;
		$info['family_switch']=$family_switch;

		//判断用户是否申请了店铺
		$shop_switch=checkShopIsPass($uid);
        $info['shop_switch']=$shop_switch;

        //判断用户是否开通了付费内容
        $info['paidprogram_switch']=checkPaidProgramIsPass($uid);

		/* 个人中心菜单 */
		$version_ios=$this->version_ios;
		$list=array();
		$list1=array();
		$list2=array();
		$list3=array();
		$shelves=1;
		if($version_ios && $version_ios==$ios_shelves){
			$agent_switch=0;
			$family_switch=0;
			$shelves=0;
		}
//        $list1[]=array('id'=>'2','name'=>T('直播小店'),'des'=>T('这里什么都有'),'thumb'=>get_upload_path("/static/appapi/images/personal/agent.png") ,'href'=>'');
//        $list1[]=array('id'=>'26','name'=>T('我的收藏'),'des'=>T('收藏好物'),'thumb'=>get_upload_path("/static/appapi/images/personal/collect.png") ,'href'=>'');

        $list2[]=array('id'=>'5','name'=>T('我的钱包'),'thumb'=>get_upload_path("/static/appapi/images/personal/renwu.png") ,'href'=>'');
        $list2[]=array('id'=>'26','name'=>T('我的收藏'),'thumb'=>get_upload_path("/static/appapi/images/personal/collect.png") ,'href'=>'');
        if($shelves){
            $list2[]=array('id'=>'1','name'=>T('我的收益'),'thumb'=>get_upload_path("/static/appapi/images/personal/votes.png"),'href'=>'' );
        }
        $list2[]=array('id'=>'2','name'=>T('我的小店'),'des'=>T('这里什么都有'),'thumb'=>get_upload_path("/static/appapi/images/personal/agent.png") ,'href'=>'');
        $list2[]=array('id'=>'4','name'=>T('上热门列表'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
        $list2[]=array('id'=>'20','name'=>T('房间管理'),'thumb'=>get_upload_path("/static/appapi/images/personal/room.png") ,'href'=>'');
        $list2[]=array('id'=>'6','name'=>T('创作者中心'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
        $list2[]=array('id'=>'7','name'=>T('红包收益'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
        $list2[]=array('id'=>'15','name'=>T('直播动态'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>get_upload_path("/Appapi/Liverecord/index") );
        $list2[]=array('id'=>'9','name'=>T('直播权限说明'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>get_upload_path("/Appapi/Liveauthority/index") );
        if($agent_switch){
            $list2[]=array('id'=>'8','name'=>T('邀请好友赚钱'),'thumb'=>get_upload_path("/static/appapi/images/personal/agent.png") ,'href'=>get_upload_path("/Appapi/Agent/index"));
		}
        $list2[]=array('id'=>'10','name'=>T('观看记录'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
        $list2[]=array('id'=>'12','name'=>T('广告管理'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
        $list2[]=array('id'=>'25','name'=>T('每日任务'),'thumb'=>get_upload_path("/static/appapi/images/personal/renwu.png") ,'href'=>'');
        $list2[]=array('id'=>'14','name'=>T('青少年模式'),'thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
        if($service_switch && $service_url){
            $list2[]=array('id'=>'21','name'=>T('在线客服'),'thumb'=>get_upload_path("/static/appapi/images/personal/kefu.png") ,'href'=>$service_url);
        }
        $list2[]=array('id'=>'13','name'=>T('个性设置'),'thumb'=>get_upload_path("/static/appapi/images/personal/set.png") ,'href'=>'');


//        $list1[]=array('id'=>'19','name'=>'我的视频','thumb'=>get_upload_path("/static/appapi/images/personal/video.png"),'href'=>'' );
//        $list1[]=array('id'=>'23','name'=>'我的动态','thumb'=>get_upload_path("/static/appapi/images/personal/dymic.png"),'href'=>'' );
		//$list1[]=array('id'=>'2','name'=>'我的'.$configpub['name_coin'],'thumb'=>get_upload_path("/static/appapi/images/personal/coin.png") ,'href'=>'');
//		$list1[]=array('id'=>'3','name'=>'我的等级','thumb'=>get_upload_path("/static/appapi/images/personal/level.png") ,'href'=>get_upload_path("/Appapi/Level/index"));
//        $list1[]=array('id'=>'11','name'=>'我的认证','thumb'=>get_upload_path("/static/appapi/images/personal/auth.png") ,'href'=>get_upload_path("/Appapi/Auth/index"));
        // $list1[]=array('id'=>'22','name'=>$configpri['shop_system_name'],'thumb'=>get_upload_path("/static/appapi/images/personal/shop.png?t=1") ,'href'=>'' ); //我的小店
        // $list1[]=array('id'=>'24','name'=>'付费内容','thumb'=>get_upload_path("/static/appapi/images/personal/pay.png") ,'href'=>'' );
//        if($shelves){
			//$list1[]=array('id'=>'14','name'=>'我的明细','thumb'=>get_upload_path("/static/appapi/images/personal/detail.png") ,'href'=>get_upload_path("/Appapi/Detail/index"));
			//$list2[]=array('id'=>'4','name'=>'在线商城','thumb'=>get_upload_path("/static/appapi/images/personal/shop.png") ,'href'=>get_upload_path("/Appapi/Mall/index"));
//			$list2[]=array('id'=>'5','name'=>'装备中心','thumb'=>get_upload_path("/static/appapi/images/personal/equipment.png") ,'href'=>get_upload_path("/Appapi/Equipment/index"));
//		}
// 		if($family_switch){
// 			$list2[]=array('id'=>'6','name'=>'家族中心','thumb'=>get_upload_path("/static/appapi/images/personal/family.png") ,'href'=>get_upload_path("/Appapi/Family/index2"));
// 			$list2[]=array('id'=>'7','name'=>'家族驻地','thumb'=>get_upload_path("/static/appapi/images/personal/family2.png") ,'href'=>get_upload_path("/Appapi/Family/home"));
// 		}
		//$list[]=array('id'=>'12','name'=>'关于我们','thumb'=>get_upload_path("/static/appapi/images/personal/about.png") ,'href'=>get_upload_path("/portal/page/lists"));

        $list[]=$list1;
        $list[]=$list2;
        $list[]=$list3;
		$info['list']=$list;
		$rs['info'][0] = $info;

		return $rs;
	}

	/**
	 * 头像上传
	 * @desc 用于用户修改头像
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].avatar 用户主头像
	 * @return string list[0].avatar_thumb 用户头像缩略图
	 * @return string msg 提示信息
	 */
	public function updateAvatar() {
		$rs = array('code' => 0 , 'msg' => T('设置头像成功'), 'info' => array());

        $avatar_str=checkNull($this->avatar);
		$checkToken=checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        //APP原生上传存储到数据库start

        if(!$avatar_str){
            $rs['code'] = 1001;
            $rs['msg'] = T('请上传头像');
            return $rs;
        }

        $configpri = getConfigPri();
        $cloudtype=$configpri['cloudtype'];

		if($cloudtype==1){
            $avatar= $avatar_str.'?imageView2/2/w/600/h/600'; //600 X 600
            $avatar_thumb= $avatar_str.'?imageView2/2/w/200/h/200'; // 200 X 200
		}else{
            $avatar = $avatar_str.'?imageMogr2/crop/600x600/gravity/center'; //600 X 600
            $avatar_thumb = $avatar_str.'?imageMogr2/crop/200x200/gravity/center'; // 200 X 200
		}
        $data=array(
            "avatar"=>get_upload_path($avatar),
            "avatar_thumb"=>get_upload_path($avatar_thumb),
        );

        $data2=array(
            "avatar"=>$avatar,
            "avatar_thumb"=>$avatar_thumb,
        );
        //APP原生上传存储到数据库end

        if(!$data){
            $rs['code'] = 1003;
			$rs['msg'] = T('更换失败，请稍候重试');
			return $rs;
        }
		/* 清除缓存 */
		delCache("userinfo_".$this->uid);
		
		$domain = new Domain_User();
		$info = $domain->userUpdate($this->uid,$data2);

		$rs['info'][0] = $data;

		return $rs;

	}

//	public function updateAvatar() {
//		$rs = array('code' => 0 , 'msg' => T('设置头像成功'), 'info' => array());
//
//		$checkToken=checkToken($this->uid,$this->token);
//		if($checkToken==700){
//			$rs['code'] = $checkToken;
//			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
//			return $rs;
//		}
//
//		if (!isset($_FILES['file'])) {
//			$rs['code'] = 1001;
//			$rs['msg'] = T("请选择上传文件");
//			return $rs;
//		}
//
//		if ($_FILES["file"]["error"] > 0) {
//			$rs['code'] = 1002;
//			$rs['msg']=T('上传失败').$_FILES["file"]["error"];
//			//$rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
//			DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
//			return $rs;
//		}
//
//        $configpri = getConfigPri();
//		$uptype=$configpri['cloudtype'];
//		$uptype=2;
//
//		if($uptype==1){
//			//七牛
//			$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name']);
//
//			if (!empty($url)) {
//				$avatar=  $url.'?imageView2/2/w/600/h/600'; //600 X 600
//				$avatar_thumb=  $url.'?imageView2/2/w/200/h/200'; // 200 X 200
//				$data=array(
//                    "avatar"=>get_upload_path($avatar),
//                    "avatar_thumb"=>get_upload_path($avatar_thumb),
//				);
//
//                $data2=array(
//					"avatar"=>$avatar,
//					"avatar_thumb"=>$avatar_thumb,
//				);
//
//
//				/* 统一服务器 格式 */
//				/* $space_host= DI()->config->get('app.Qiniu.space_host');
//				$avatar2=str_replace($space_host.'/', "", $avatar);
//				$avatar_thumb2=str_replace($space_host.'/', "", $avatar_thumb);
//				$data2=array(
//					"avatar"=>$avatar2,
//					"avatar_thumb"=>$avatar_thumb2,
//				); */
//			}
//		}else if($uptype==2){
//
//            $avatar=  $res['filepath'].'?imageMogr2/crop/600x600/gravity/center'; //600 X 600
//            $avatar_thumb=  $res['filepath'].'?imageMogr2/crop/200x200/gravity/center'; // 200 X 200
//			$data=array(
//				"avatar"=>get_upload_path($avatar),
//				"avatar_thumb"=>get_upload_path($avatar_thumb),
//			);
//
//            $data2=array(
//				"avatar"=>$avatar,
//				"avatar_thumb"=>$avatar_thumb,
//			);
//
//		}else{
//			//本地上传
//			//设置上传路径 设置方法参考3.2
//			DI()->ucloud->set('save_path','avatar/'.date("Ymd"));
//
//			//新增修改文件名设置上传的文件名称
//		   // DI()->ucloud->set('file_name', $this->uid);
//
//			//上传表单名
//			$res = DI()->ucloud->upfile($_FILES['file']);
//
//			$files='../upload'.$res['file'];
//			$newfiles=str_replace(".png","_thumb.png",$files);
//			$newfiles=str_replace(".jpg","_thumb.jpg",$newfiles);
//			$newfiles=str_replace(".gif","_thumb.gif",$newfiles);
//			$PhalApi_Image = new Image_Lite();
//			//打开图片
//			$PhalApi_Image->open($files);
//			/**
//			 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
//			 * IMAGE_THUMB_SCALING      //常量，标识缩略图等比例缩放类型
//			 * IMAGE_THUMB_FILLED       //常量，标识缩略图缩放后填充类型
//			 * IMAGE_THUMB_CENTER       //常量，标识缩略图居中裁剪类型
//			 * IMAGE_THUMB_NORTHWEST    //常量，标识缩略图左上角裁剪类型
//			 * IMAGE_THUMB_SOUTHEAST    //常量，标识缩略图右下角裁剪类型
//			 * IMAGE_THUMB_FIXED        //常量，标识缩略图固定尺寸缩放类型
//			 */
//
//			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
//
//			$PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
//			$PhalApi_Image->save($files);
//
//			$PhalApi_Image->thumb(200, 200, IMAGE_THUMB_SCALING);
//			$PhalApi_Image->save($newfiles);
//
//			$avatar=  '/upload'.$res['file']; //600 X 600
//
//			$avatar_thumb=str_replace(".png","_thumb.png",$avatar);
//			$avatar_thumb=str_replace(".jpg","_thumb.jpg",$avatar_thumb);
//			$avatar_thumb=str_replace(".gif","_thumb.gif",$avatar_thumb);
//
//			$data=array(
//				"avatar"=>get_upload_path($avatar),
//				"avatar_thumb"=>get_upload_path($avatar_thumb),
//			);
//
//            $data2=array(
//				"avatar"=>$avatar,
//				"avatar_thumb"=>$avatar_thumb,
//			);
//
//		}
//
//		@unlink($_FILES['file']['tmp_name']);
//        if(!$data){
//            $rs['code'] = 1003;
//			$rs['msg'] = T('更换失败，请稍候重试');
//			return $rs;
//        }
//		/* 清除缓存 */
//		delCache("userinfo_".$this->uid);
//
//		$domain = new Domain_User();
//		$info = $domain->userUpdate($this->uid,$data2);
//
//		$rs['info'][0] = $data;
//
//		return $rs;
//
//	}
	
	/**
	 * 修改用户信息
	 * @desc 用于修改用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 修改成功提示信息 
	 * @return string msg 提示信息
	 */
	public function updateFields() {
		$rs = array('code' => 0, 'msg' => T('修改成功'), 'info' => array());
		
		$checkToken=checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		$fields=json_decode($this->fields,true);
		
        $allow=['user_nicename','sex','signature','birthday','location','province','city'];
		$domain = new Domain_User();
		foreach($fields as $k=>$v){
            if(in_array($k,$allow)){
                $fields[$k]=checkNull($v);
            }else{
                unset($fields[$k]);
            }
			
		}
		
		if(array_key_exists('user_nicename', $fields)){
			if($fields['user_nicename']==''){
				$rs['code'] = 1002;
				$rs['msg'] = T('昵称不能为空');
				return $rs;
			}
			$isexist = $domain->checkName($this->uid,$fields['user_nicename']);
			if(!$isexist){
				$rs['code'] = 1002;
				$rs['msg'] = T('昵称重复，请修改');
				return $rs;
			}

			if(strstr($fields['user_nicename'], '已注销')!==false){ //昵称包含已注销三个字
				$rs['code'] = 10011;
				$rs['msg'] = T('输入非法，请重新输入');
				return $rs;
			}

			if(mb_substr($fields['user_nicename'], 0,1)=='='){
				$rs['code'] = 10011;
				$rs['msg'] = T('输入非法，请重新输入');
				return $rs;
			}


			//$fields['user_nicename']=filterField($fields['user_nicename']);
            $sensitivewords=sensitiveField($fields['user_nicename']);
			if($sensitivewords==1001){
				$rs['code'] = 10011;
				$rs['msg'] = T('输入非法，请重新输入');
				return $rs;
			}
		}
		if(array_key_exists('signature', $fields)){
			$sensitivewords=sensitiveField($fields['signature']);
			if($sensitivewords==1001){
				$rs['code'] = 10011;
				$rs['msg'] = T('输入非法，请重新输入');
				return $rs;
			}
		}
        
        if(array_key_exists('birthday', $fields)){
			$fields['birthday']=strtotime($fields['birthday']);
		}
        
		$info = $domain->userUpdate($this->uid,$fields);
	 
		if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = T('修改失败');
			return $rs;
		}
		/* 清除缓存 */
		delCache("userinfo_".$this->uid);
		$rs['info'][0]['msg']=T('修改成功');
		return $rs;
	}

	/**
	 * 修改密码
	 * @desc 用于修改用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 修改成功提示信息
	 * @return string msg 提示信息
	 */
	public function updatePass() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$old_pass=checkNull($this->old_pass);
		$pass=checkNull($this->pass);
		$pass2=checkNull($this->pass2);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		if($pass != $pass2){
			$rs['code'] = 1002;
			$rs['msg'] = T('两次新密码不一致');
			return $rs;
		}

		if($old_pass == $pass){
			$rs['code'] = 1002;
			$rs['msg'] = T('新密码与旧密码不能一致');
			return $rs;
		}
		
		$check = passcheck($pass);
		if(!$check ){
			$rs['code'] = 1004;
			$rs['msg'] = T('密码为6-20位字母数字组合');
			return $rs;										
		}
		
		$domain = new Domain_User();
		$info = $domain->updatePass($uid,$old_pass,$pass);
	 
		if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = T('旧密码错误');
			return $rs;
		}else if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = T('修改失败');
			return $rs;
		}

		$rs['info'][0]['msg']=T('修改成功');
		return $rs;
	}

	/**
	 * 设置支付密码
	 * @desc 用于修改用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string list[0].msg 修改成功提示信息
	 * @return string msg 提示信息
	 */
	public function updatePayPass() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$pay_pass=checkNull($this->pay_pass);
		$pay_pass2=checkNull($this->pay_pass2);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		if($pay_pass != $pay_pass2){
			$rs['code'] = 1002;
			$rs['msg'] = T('两次新密码不一致');
			return $rs;
		}

		$check = passcheckpay($pay_pass);
		if(!$check ){
			$rs['code'] = 1004;
			$rs['msg'] = T('密码为6位数字组合');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->updatePayPass($uid,$pay_pass);

		if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = T('旧密码错误');
			return $rs;
		}else if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = T('修改失败');
			return $rs;
		}

		$rs['info'][0]['msg']=T('修改成功');
		return $rs;
	}

	/**
	 * 设置BNB地址
	 * @desc 用于修改BNB地址
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string list[0].msg 修改成功提示信息
	 * @return string msg 提示信息
	 */
	public function updateBnbAdr() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$bnb_adr=checkNull($this->bnb_adr);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->updateBnbAdr($uid,$bnb_adr);

		if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = T('旧密码错误');
			return $rs;
		}else if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = T('修改失败');
			return $rs;
		}

		$rs['info'][0]['msg']=T('修改成功');
		return $rs;
	}
	
	/**
	 * 我的钻石
	 * @desc 用于获取用户钻石，lala
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getBalance() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $type=checkNull($this->type);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getBalance($uid);

		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 我的链
	 * @desc 用于获取链的类型
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getChain() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $paylist=[];

        $paylist[]=[
            'id'=>'bsc',
            'name'=>T('BNB Smart Chain (BSC)'),
            'tips'=>T('充值到账: 20次确认'),
            'thumb'=>get_upload_path("/static/app/pay/bsc.png"),
            'href'=>'',
            "contract_tips"=>T('*合约信息***97955'),
        ];

//        $paylist[]=[
//            'id'=>'tron',
//            'name'=>T('TRON (TRC20)'),
//            'tips'=>T('充值到账: 20次确认'),
//            'thumb'=>get_upload_path("/static/app/pay/tron.png"),
//            'href'=>'',
//        ];
//
//        $paylist[]=[
//            'id'=>'solana',
//            'name'=>T('Solana (SOL)'),
//            'tips'=>T('充值到账: 20次确认'),
//            'thumb'=>get_upload_path("/static/app/pay/solana.png"),
//            'href'=>'',
//        ];
//
        $paylist[]=[
            'id'=>'pop',
            'name'=>T('PopChain（POP）'),
            'tips'=>T('充值到账: 20次确认'),
            'thumb'=>get_upload_path("/static/app/pay/eth.png"),
            'href'=>'',
            "contract_tips"=>T('*合约信息***2F4EC3'),
        ];


        $info['paylist'] =$paylist;
        $info['tip_t'] =T('选择链类型');
        $info['tip_d'] =T('* 请确保充值时所选的链类型与提币时所选的链类型一致');

		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 我的USDT
	 * @desc 用于获取链的类型
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getMyUsdtInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_User();
        $info = $domain->getMyUsdtInfo($uid);

		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 我的链详情
	 * @desc 用于获取链的详情
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getChainDetail() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $id=checkNull($this->id);
//        $source=checkNull($this->source);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $configpub=getConfigPub();
        $configpri=getConfigPri();

        $curlPost['chainType'] = $id;
        $curlPost['userId'] = $uid;
        $curlPost = [];
        $re = curlPost($curlPost,'http://172.31.25.128:3010/api/wallet/query?chainType='.$id.'&userId='.$uid);

        if(!$re){
            $rs['code'] = 403;
            $rs['msg'] = T('钱包中心数据异常');
            return $rs;
        }
        $re = json_decode($re,true);
        if($re['code']!=200){
            $rs['code'] = $re['code'];
            $rs['msg'] = T('获取失败！');
            return $rs;
        }

        $content = $re['content'];
        require API_ROOT . '/../sdk/phpqrcode/phpqrcode.php';
        // 生成二维码
        $level = 'L';
        $size = 5;
        $margin = 2;
        QRcode::png($content,false,$level,$size,$margin);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean(); //清除缓冲区的内容，并将缓冲区关闭，但不会输出内容
        $img = 'data:image/jpg;base64,'.$imageString;

//        if($source=='balance'){
//            addWalletBalance($content);
//        }else{
//            delWalletBalance($content);
//        }
//        echo '<img src='.$img.'/>';
//        exit();

        $paydetail=[
            'id'=>$id,
            'adr'=>$content,
            'qr'=>$img,
            'zx'=>'1 USDT',
            'cb'=>'20 '.T('个网络确认'),
            'tb'=>'2 '.T('个网络确认'),
            'tips'=>T('* 您只能向此地址充入 ').'USDT'.'-'.strtoupper($id).T('，充入其他资产将无法找回'),
        ];

		$rs['info'][0]=$paydetail;
		return $rs;
	}

    /**
     * 检测链充值是否成功
     * @desc 用于获取链的详情
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function checkChainOrder() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $adr=checkNull($this->adr);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $status=checkChainOrder($uid,$adr);

        $rs['info'][0]['status']=$status;
        return $rs;

    }

    /**
     * 转出USDT
     * @desc 用于转出USDT
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function forwardChainUsdt() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $adr=checkNull($this->adr);
        $chainType=checkNull($this->chainType);
        $number=checkNull($this->number);
        $user_pay_pass=checkNull($this->user_pay_pass);
        $timestamp=checkNull($this->timestamp);
        $nonce=checkNull($this->nonce);
        $sign=checkNull($this->sign);

        $checkdata=array(
            'uid'=>$uid,
            'adr'=>$adr,
            'number'=>$number,
            'timestamp'=>$timestamp,
            'nonce'=>$nonce,
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']=T('签名错误');
            return $rs;
        }

        $key = 'getNonce_'.$uid.'_'.$nonce;
        $get_nonce = getcaches($key);
        if ($get_nonce) {
            $rs['code']=1001;
            $rs['msg']=T('非法操作');
            return $rs;
        }else{
            setcaches($key,1,300);
        }

        $now = time();
        $timestamp = (int)$timestamp+300;
        if($now>$timestamp){
            $rs['code']=1001;
            $rs['msg']=T('非法操作');
            return $rs;
        }

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $config=getConfigPri();
        $domain = new Domain_User();
        $info = $domain->forwardChainUsdt($uid,$adr,$chainType,$number,$user_pay_pass);
        if($info==1001){
            $rs['code'] = 1001;
            $rs['msg'] = T('您输入的金额大于可提现金额');
            return $rs;
        }else if($info==1031){
            $rs['code'] = 1031;
            $rs['msg'] = T('支付密码错误');
            return $rs;
        }else if($info==1032){
            $rs['code'] = 1032;
            $rs['msg'] = T('未设置支付密码，请先设置');
            return $rs;
        }else if($info==1003){
            $rs['code'] = 1003;
            $rs['msg'] = T('请先进行身份认证');
            return $rs;
        }else if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = T('提现最低额度为{cash_min}元',['cash_min'=>$config['cash_min']]);
            return $rs;
        }else if($info==1005){
            $rs['code'] = 1005;
            $rs['msg'] = T('不在提现期限内，不能提现');
            return $rs;
        }else if($info==1006){
            $rs['code'] = 1006;
            $rs['msg'] = T('每月只可提现{cash_max_times}次,已达上限',['cash_max_times'=>$config['cash_max_times']]);
            return $rs;
        }else if($info==1007){
            $rs['code'] = 1007;
            $rs['msg'] = T('提现账号信息不正确');
            return $rs;
        }else if(!$info){
            $rs['code'] = 1002;
            $rs['msg'] = T('提现失败，请重试');
            return $rs;
        }

        if(isset($info['code'])&&$info['code']==400){
            return $info;
        }

        $rs['info'][0]['msg']=T('提现成功');
        return $rs;

        $rs['info'][0]['status']=$status;
        return $rs;

    }

	/**
	 * 我的收益提现方式获取
	 * @desc 用于获取用户余额 提现方式信息
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string info[0].coin 用户钻石余额
	 * @return string msg 提示信息
	 */
	public function getAccountType() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $type=checkNull($this->type);
        $version_ios=checkNull($this->version_ios);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getBalance($uid);

        $paylist=[];

        $paylist[]=[
            'id'=>'ali',
            'name'=>T('支付宝'),
            'thumb'=>get_upload_path("/static/app/pay/ali.png"),
            'href'=>'',
            'type'=>1,
        ];


        $paylist[]=[
            'id'=>'wx',
            'name'=>T('微信'),
            'thumb'=>get_upload_path("/static/app/pay/wx.png"),
            'href'=>'',
            'type'=>2,
        ];

        $paylist[]=[
            'id'=>'bank',
            'name'=>T('银行卡'),
            'thumb'=>get_upload_path("/static/app/pay/bank.png"),
            'href'=>'',
            'type'=>3,
        ];

        $info['paylist'] =$paylist;

		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 我的VIP充值
	 * @desc 用于获取用户余额,充值规则 支付方式信息
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return array info[0].rules 充值规则
	 * @return string info[0].rules[].id 充值规则
	 * @return string info[0].rules[].name 名称
	 * @return string info[0].rules[].name_en 英文名称
	 * @return string info[0].rules[].money 价格
	 * @return string info[0].rules[].days 充值天数
	 * @return string info[0].rules[].coin 钻石数
	 * @return string info[0].aliapp_switch 支付宝开关，0表示关闭，1表示开启
	 * @return string info[0].aliapp_partner 支付宝合作者身份ID
	 * @return string info[0].aliapp_seller_id 支付宝帐号
	 * @return string info[0].aliapp_key_android 支付宝安卓密钥
	 * @return string info[0].aliapp_key_ios 支付宝苹果密钥
	 * @return string info[0].wx_switch 微信支付开关，0表示关闭，1表示开启
	 * @return string info[0].wx_appid 开放平台账号AppID
	 * @return string info[0].wx_appsecret 微信应用appsecret
	 * @return string info[0].wx_mchid 微信商户号mchid
	 * @return string info[0].wx_key 微信密钥key
	 * @return string msg 提示信息
	 */
	public function getVipCharge() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $type=checkNull($this->type);
        $version_ios=checkNull($this->version_ios);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();

		$key='getVipChargeRules';
		$rules=getcaches($key);
		if(!$rules){
			$rules= $domain->getVipChargeRules();
			setcaches($key,$rules);
		}
		$info['rules'] =$rules;

		$configpub=getConfigPub();
		$configpri=getConfigPri();

		$aliapp_switch=$configpri['vip_aliapp_switch'];
        $wx_switch=$configpri['vip_wx_switch'];
        $apple_switch=$configpri['vip_apple_switch'];


		$info['aliapp_switch']=$aliapp_switch;
		$info['aliapp_partner']=$aliapp_switch==1?$configpri['aliapp_partner']:'';
		$info['aliapp_seller_id']=$aliapp_switch==1?$configpri['aliapp_seller_id']:'';
		$info['aliapp_key_android']=$aliapp_switch==1?$configpri['aliapp_key_android']:'';
		$info['aliapp_key_ios']=$aliapp_switch==1?$configpri['aliapp_key_ios']:'';

		$info['wx_switch']=$wx_switch;
		$info['wx_appid']=$wx_switch==1?$configpri['wx_appid']:'';
		$info['wx_appsecret']=$wx_switch==1?$configpri['wx_appsecret']:'';
		$info['wx_mchid']=$wx_switch==1?$configpri['wx_mchid']:'';
		$info['wx_key']=$wx_switch==1?$configpri['wx_key']:'';

        $aliscan_switch=$configpri['aliscan_switch'];

        $wx_mini_switch=$configpri['wx_mini_switch'];
        $info['wx_mini_switch']=$wx_mini_switch;

        /* 支付列表 */
        $shelves=1;
        $ios_shelves=$configpub['ios_shelves'];
        if($version_ios && $version_ios==$ios_shelves){
			$shelves=0;
		}

        $paylist=[];
        if($aliapp_switch && $shelves){
            $paylist[]=[
                'id'=>'ali',
                'name'=>T('支付宝支付'),
                'thumb'=>get_upload_path("/static/app/pay/ali.png"),
                'href'=>'',
            ];
        }

        if($wx_switch && $shelves){
            $paylist[]=[
                'id'=>'wx',
                'name'=>T('微信支付'),
                'thumb'=>get_upload_path("/static/app/pay/wx.png"),
                'href'=>'',
            ];
        }

        if($apple_switch && $shelves==0 && $type==1){
            $paylist[]=[
                'id'=>'apple',
                'name'=>T('苹果支付'),
                'thumb'=>get_upload_path("/static/app/pay/apple.png"),
                'href'=>'',
            ];
        }

        $paylist[]=[
            'id'=>'coin',
            'name'=>T('余额支付'),
            'thumb'=>get_upload_path("/static/app/pay/wx.png"),
            'href'=>'',
        ];

        $info['paylist'] =$paylist;
        $info['tip_t'] =$configpub['name_coin'].'/'.$configpub['name_score'].T('说明:');
        $info['tip_d'] =T('{name_coin}可通过平台提供的支付方式进行充值获得，{name_coin}适用于平台内所有消费；{name_score}可通过直播间内游戏奖励获得，所得{name_score}可用于平台商城内兑换会员、坐 骑、靓号等服务，不可提现。',['name_coin'=>$configpub['name_score'],'name_score'=>$configpub['name_score']]);

        $rs['info'][0]=$info;
		return $rs;
	}

    /**
     * 余额支付充值VIP
     * @desc 用于余额支付充值VIP
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 提现成功信息
     * @return string msg 提示信息
     */
    public function setVipBalance() {
        $rs = array('code' => 0, 'msg' => T('支付成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $rules_id=checkNull($this->rules_id);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $data=array(
            'uid'=>$uid,
            'rules_id'=>$rules_id,
        );
        $domain = new Domain_User();
        $info = $domain->setVipBalance($data);
        if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = T('你的余额不足');
            return $rs;
        }else if($info==1001){
            $rs['code'] = 1003;
            $rs['msg'] = T('扣费失败');
            return $rs;
        }else if(!$info){
            $rs['code'] = 1002;
            $rs['msg'] = T('失败，请重试');
            return $rs;
        }

        $rs['info'][0]['msg']=T('支付成功');
        return $rs;
    }
	
	/**
	 * 我的收益
	 * @desc 用于获取用户收益，包括可体现金额，今日可提现金额
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votes 可提取映票数
	 * @return string info[0].votestotal 总映票
	 * @return string info[0].cash_rate 映票兑换比例
	 * @return string info[0].total 可提现金额
	 * @return string info[0].tips 温馨提示
	 * @return string msg 提示信息
	 */
	public function getProfit() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		} 
		
		$domain = new Domain_User();
		$info = $domain->getProfit($uid);
	 
		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 我的USDT转出
	 * @desc 用于我的USDT转出页面
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string info[0].usdt 可提取usdt
	 * @return string info[0].cash_rate 映票兑换比例
	 * @return string info[0].tips 温馨提示
	 * @return string msg 提示信息
	 */
	public function getUsdtForward() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getUsdtForward($uid);

		$rs['info'][0]=$info;
		return $rs;
	}

    /**
     * 红包收益
     * @desc 用于获取用户收益，包括可体现金额，今日可提现金额
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].votes 可提取映票数
     * @return string info[0].votestotal 总映票
     * @return string info[0].cash_rate 映票兑换比例
     * @return string info[0].total 可提现金额
     * @return string info[0].tips 温馨提示
     * @return string msg 提示信息
     */
    public function getRedProfit() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $info = $domain->getRedProfit($uid);

        $rs['info'][0]=$info;
        return $rs;
    }

    /**
	 * 用户提现
	 * @desc 用于进行用户提现
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 提现成功信息
	 * @return string msg 提示信息
	 */
	public function setCash() {
		$rs = array('code' => 0, 'msg' => T('提现成功'), 'info' => array());
        exit();
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);		
        $accountid=checkNull($this->accountid);		
        $cashvote=checkNull($this->cashvote);		
        
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        if(!$accountid){
            $rs['code'] = 1001;
			$rs['msg'] = T('请选择提现账号');
			return $rs;
        }
        
        if(!$cashvote){
            $rs['code'] = 1002;
			$rs['msg'] = T('请输入有效的提现票数');
			return $rs;
        }
		
        $data=array(
            'uid'=>$uid,
            'accountid'=>$accountid,
            'cashvote'=>$cashvote,
        );
        $config=getConfigPri();
		$domain = new Domain_User();
		$info = $domain->setCash($data);
		if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = T('您输入的金额大于可提现金额');
			return $rs;
		}else if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = T('请先进行身份认证');
			return $rs;
		}else if($info==1004){
			$rs['code'] = 1004;
			$rs['msg'] = T('提现最低额度为{cash_min}元',['cash_min'=>$config['cash_min']]);
			return $rs;
		}else if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = T('不在提现期限内，不能提现');
			return $rs;
		}else if($info==1006){
			$rs['code'] = 1006;
			$rs['msg'] = T('每月只可提现{cash_max_times}次,已达上限',['cash_max_times'=>$config['cash_max_times']]);
			return $rs;
		}else if($info==1007){
			$rs['code'] = 1007;
			$rs['msg'] = T('提现账号信息不正确');
			return $rs;
		}else if(!$info){
			$rs['code'] = 1002;
			$rs['msg'] = T('提现失败，请重试');
			return $rs;
		}
	 
		$rs['info'][0]['msg']=T('提现成功');
		return $rs;
	}

    /**
	 * 红包收益-用户提现
	 * @desc 用于红包收益-进行用户提现
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string info[0].msg 提现成功信息
	 * @return string msg 提示信息
	 */
	public function setRedCash() {
		$rs = array('code' => 0, 'msg' => T('提现成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $accountid=checkNull($this->accountid);
        $cashvote=checkNull($this->cashvote);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        if(!$accountid){
            $rs['code'] = 1001;
			$rs['msg'] = T('请选择提现账号');
			return $rs;
        }

        if(!$cashvote){
            $rs['code'] = 1002;
			$rs['msg'] = T('请输入有效的提现票数');
			return $rs;
        }

        $data=array(
            'uid'=>$uid,
            'accountid'=>$accountid,
            'cashvote'=>$cashvote,
        );
        $config=getConfigPri();
		$domain = new Domain_User();
		$info = $domain->setRedCash($data);
		if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = T('您输入的金额大于可提现金额');
			return $rs;
		}else if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = T('请先进行身份认证');
			return $rs;
		}else if($info==1004){
			$rs['code'] = 1004;
            $rs['msg'] = T('提现最低额度为{cash_min}元',['cash_min'=>$config['cash_min']]);
            return $rs;
        }else if($info==1005){
            $rs['code'] = 1005;
            $rs['msg'] = T('不在提现期限内，不能提现');
            return $rs;
        }else if($info==1006){
            $rs['code'] = 1006;
            $rs['msg'] = T('每月只可提现{cash_max_times}次,已达上限',['cash_max_times'=>$config['cash_max_times']]);
			return $rs;
		}else if($info==1007){
			$rs['code'] = 1007;
			$rs['msg'] = T('提现账号信息不正确');
			return $rs;
		}else if(!$info){
			$rs['code'] = 1002;
			$rs['msg'] = T('提现失败，请重试');
			return $rs;
		}

		$rs['info'][0]['msg']=T('提现成功');
		return $rs;
	}
	/**
	 * 判断是否关注
	 * @desc 用于判断是否关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent 关注信息，0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function isAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$info = isAttention($uid,$touid);
	 
		$rs['info'][0]['isattent']=(string)$info;
		return $rs;
	}			
	
	/**
	 * 关注/取消关注
	 * @desc 用于关注/取消关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent 关注信息，0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function setAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);

		if($uid==$touid){
			$rs['code']=1001;
			$rs['msg']=T('不能关注自己');
			return $rs;	
		}
		$domain = new Domain_User();
		$info = $domain->setAttent($uid,$touid);
	 
		$rs['info'][0]['isattent']=(string)$info;
		return $rs;
	}			
	
	/**
	 * 判断是否拉黑
	 * @desc 用于判断是否拉黑
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent  拉黑信息,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function isBlacked() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());
			
			$uid=checkNull($this->uid);
			$touid=checkNull($this->touid);

			$info = isBlack($uid,$touid);
		 
			$rs['info'][0]['isblack']=(string)$info;
			return $rs;
	}	

	/**
	 * 检测拉黑状态
	 * @desc 用于私信聊天时判断私聊双方的拉黑状态
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].u2t  是否拉黑对方,0表示未拉黑，1表示已拉黑
	 * @return string info[0].t2u  是否被对方拉黑,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function checkBlack() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());

			$uid=checkNull($this->uid);
			$touid=checkNull($this->touid);

			//判断对方是否已注销
			$is_destroy=checkIsDestroyByUid($touid);
			if($is_destroy){
				$rs['code']=1001;
				$rs['msg']=T('对方已注销');
				return $rs;
			}
			
			$u2t = isBlack($uid,$touid);
			$t2u = isBlack($touid,$uid);
		 
			$rs['info'][0]['u2t']=(string)$u2t;
			$rs['info'][0]['t2u']=(string)$t2u;
			return $rs;
	}			
		
	/**
	 * 拉黑/取消拉黑
	 * @desc 用于拉黑/取消拉黑
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isblack 拉黑信息,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function setBlack() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());
			
			$uid=checkNull($this->uid);
			$touid=checkNull($this->touid);

			$domain = new Domain_User();
			$info = $domain->setBlack($uid,$touid);
		 
			$rs['info'][0]['isblack']=(string)$info;
			return $rs;
	}		
	
	/**
	 * 获取绑定手机号短信验证码
	 * @desc 用于找回密码获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return array info[0]  
	 * @return string msg 提示信息
	 */
	 
	public function getBindCode() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

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

		if($_SESSION['country_code']==$country_code && $_SESSION['set_mobile']==$mobile && $_SESSION['set_mobile_expiretime']> time() ){
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
		$result=sendCode($mobile,$mobile_code);
		if($result['code']===0){
            $_SESSION['country_code'] = $country_code;
			$_SESSION['set_mobile'] = $mobile;
			$_SESSION['set_mobile_code'] = $mobile_code;
			$_SESSION['set_mobile_expiretime'] = time() +60*5;	
		}else if($result['code']==667){
            $_SESSION['country_code'] = $country_code;
			$_SESSION['set_mobile'] = $mobile;
            $_SESSION['set_mobile_code'] = $result['msg'];
            $_SESSION['set_mobile_expiretime'] = time() +60*5;
            
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
	 * 绑定手机号
	 * @desc 用于用户绑定手机号
	 * @return int code 操作码，0表示成功，非0表示有错误
	 * @return array info 
	 * @return object info[0].msg 绑定成功提示
	 * @return string msg 提示信息
	 */
	public function setMobile() {

		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$mobile=checkNull($this->mobile);
		$country_code=checkNull($this->country_code);
//		$code=checkNull($this->code);
//
//		if($mobile!=$_SESSION['set_mobile']){
//			$rs['code'] = 1001;
//			$rs['msg'] = T('手机号码不一致');
//			return $rs;
//		}
//
//		if($code!=$_SESSION['set_mobile_code']){
//			$rs['code'] = 1002;
//			$rs['msg'] = T('验证码错误');
//			return $rs;
//		}

        if(strlen($mobile)<8){
            $rs['code'] = 1001;
            $rs['msg'] = T('手机号格式错误');
            return $rs;
        }

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
			
		$domain = new Domain_User();

		//更新数据库
		$data=array("mobile"=>$mobile,"country_code"=>$country_code);
		$result = $domain->userUpdateMobile($uid,$data);
		if($result===false){
			$rs['code'] = 1003;
			$rs['msg'] = T('一个手机号最多绑定10个账号');
			return $rs;
		}
	
		$rs['info'][0]['msg'] = T('绑定成功');

		return $rs;
	}

    /**
     * 我的上热门视频列表
     * @desc 用于我的上热门视频列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getPopularVideoList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $token=checkNull($this->token);
        $status=checkNull($this->status);
        $p = checkNull($this->p);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $info = $domain->getPopularVideoList($uid, $p, $status);

        $rs['info'] = $info;
        return $rs;
    }

    /**
     * 我的上热门直播列表
     * @desc 用于我的上热门直播列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getPopularLiveList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $token=checkNull($this->token);
        $status=checkNull($this->status);
        $p = checkNull($this->p);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $info = $domain->getPopularLiveList($uid, $p, $status);

        $rs['info'] = $info;
        return $rs;
    }
	
	/**
	 * 关注列表
	 * @desc 用于获取用户的关注列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].isattent 是否关注,0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function getFollowsList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);
		$user_nicename=checkNull($this->user_nicename);

		$domain = new Domain_User();
		$info = $domain->getFollowsList($uid,$touid,$p,$user_nicename);
	 
		$rs['info']=$info;
		return $rs;
	}

	/**
	 * 点赞我的人列表
	 * @desc 用于获取点赞我的人列表
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getLikesList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $p = checkNull($this->p);

        $domain = new Domain_User();
        $info = $domain->getLikesList($uid, $p);

        $rs['info'] = $info;
        return $rs;
    }

	/**
	 * 评论我的人列表
	 * @desc 用于获取评论我的人列表
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getCommentsList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $p = checkNull($this->p);

        $domain = new Domain_User();
        $info = $domain->getCommentsList($uid, $p);

        $rs['info'] = $info;
        return $rs;
    }


	/**
	 * @我的人列表
	 * @desc 用于获取@我的人列表
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getAtsList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$p=checkNull($this->p);

		$domain = new Domain_User();
		$info = $domain->getAtsList($uid,$p);

		$rs['info']=$info;
		return $rs;
	}
	
	/**
	 * 粉丝列表
	 * @desc 用于获取用户的粉丝列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].isattent 是否关注,0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function getFansList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);
		$status=checkNull($this->status);
		$keyword=checkNull($this->keyword);

		$domain = new Domain_User();
		$info = $domain->getFansList($uid,$touid,$p,$status,$keyword);
	 
		$rs['info']=$info;
		return $rs;
	}	

	/**
	 * 黑名单列表
	 * @desc 用于获取用户的黑名单列表
	 * @return int code 操作码，0表示成功
	 * @return array info 用户基本信息
	 * @return string msg 提示信息
	 */
	public function getBlackList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$domain = new Domain_User();
		$info = $domain->getBlackList($uid,$touid,$p);
	 
		$rs['info']=$info;
		return $rs;
	}		
	
	/**
	 * 直播记录
	 * @desc 用于获取用户的直播记录
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].nums 观看人数
	 * @return string info[].datestarttime 格式化的开播时间
	 * @return string info[].dateendtime 格式化的结束时间
	 * @return string info[].video_url 回放地址
	 * @return string info[].file_id 回放标示
	 * @return string msg 提示信息
	 */
	public function getLiverecord() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$domain = new Domain_User();
		$info = $domain->getLiverecord($touid,$p);
	 
		$rs['info']=$info;
		return $rs;
	}	

    /**
     *获取阿里云cdn录播地址
     *@desc 如果使用的阿里云cdn，则使用该接口获取录播地址
     *@return int code 操作码，0表示成功
     *@return string info[0].url 录播视频地址
	 * @return string msg 提示信息
    */		
    public function getAliCdnRecord(){
        $rs = array('code' => 0,'msg' => '', 'info' => array());

        $id=checkNull($this->id);
        $domain = new Domain_Cdnrecord();
        $info = $domain->getCdnRecord($id);
        
        if(!$info['video_url']){
            $rs['code']=1002;
            $rs['msg']=T('直播回放不存在');
            return $rs;
        }

        $rs['info'][0]['url']=$info['video_url'];

        return $rs;
    }	


	/**
	 * 个人主页 
	 * @desc 用于获取个人主页数据
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].isattention 是否关注，0表示未关注，1表示已关注
	 * @return string info[0].isblack 我是否拉黑对方，0表示未拉黑，1表示已拉黑
	 * @return string info[0].isblack2 对方是否拉黑我，0表示未拉黑，1表示已拉黑
	 * @return array info[0].contribute 贡献榜前三
	 * @return array info[0].contribute[].avatar 头像
	 * @return string info[0].islive 是否正在直播，0表示未直播，1表示直播
	 * @return string info[0].videonums 视频数
	 * @return string info[0].likevideonums 喜欢视频数
	 * @return string info[0].livenums 直播数
	 * @return array info[0].liverecord 直播记录
	 * @return array info[0].label 印象标签
	 * @return string info[0].isshop 是否有店铺，0否1是
	 * @return object info[0].shop 店铺信息
	 * @return string info[0].shop.name 名称
	 * @return string info[0].shop.thumb 封面
	 * @return string info[0].shop.nums 商品数量
     * @return string info[0].likes 喜欢视频数量
     * @return string info[0].age 年龄
     * @return string info[0].sex 性别;0:保密,1:男,2:女
	 * @return string msg 提示信息
	 */
	public function getUserHome() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);
        
		$domain = new Domain_User();
		$info=$domain->getUserHome($uid,$touid);
        
        /* 守护 */
        $data=array(
			"liveuid"=>$touid,
		);

		$domain_guard = new Domain_Guard();
		$guardlist = $domain_guard->getGuardList($data);
        
        $info['guardlist']=array_slice($guardlist,0,3);
        
        /* 标签 */
        $key="getMyLabel_".$touid;
        $label=getcaches($key);
        if(!$label){
            $label = $domain->getMyLabel($touid);
            setcaches($key,$label); 
        }
        
        $labels=array_slice($label,0,3);
        
        $info['label']=$labels;
        
        /* 视频 */
        $domain_video = new Domain_Video();
		$video = $domain_video->getHomeVideo($uid,$touid,1);
        
        $info['videolist']=$video;
        
        /* 店铺 */
        $isshop='0';
        $shop=(object)[];
        
        $domain_shop = new Domain_Shop();
		$shopinfo = $domain_shop->getShop($touid);
        if($shopinfo && $shopinfo['status']=="1"){
            $isshop='1';

            
            $where=[
                'uid'=>$touid,
                'status'=>1,
            ];
            $nums = $domain_shop->countGoods($where);
            
            $shopinfo['nums']=$nums;
            $shop=$shopinfo;
        }
        
        $info['isshop']=$isshop;
        $info['shop']=$shop;
		
		$rs['info'][0]=$info;
		return $rs;
	}		

	/**
	 * 贡献榜 
	 * @desc 用于获取贡献榜
	 * @return int code 操作码，0表示成功
	 * @return array info 排行榜列表
	 * @return string info[].total 贡献总数
	 * @return string info[].userinfo 用户信息
	 * @return string msg 提示信息
	 */
	public function getContributeList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$domain = new Domain_User();
		$info=$domain->getContributeList($touid,$p);
		
		$rs['info']=$info;
		return $rs;
	}	
	
	/**
     * 私信用户信息
     * @desc 用于获取其他用户基本信息
     * @return int code 操作码，0表示成功，1表示用户不存在
     * @return array info   
     * @return string info[0].id 用户ID
     * @return string info[0].isattention 我是否关注对方，0未关注，1已关注
     * @return string info[0].isattention2 对方是否关注我，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function getPmUserInfo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
		$touid=checkNull($this->touid);

        $info = getUserInfo($touid);
		 if (empty($info)) {
            $rs['code'] = 1001;
            $rs['msg'] = T('用户不存在');
            return $rs;
        }
        $info['isattention2']= (string)isAttention($touid,$uid);
        $info['isattention']= (string)isAttention($uid,$touid);
       
        $rs['info'][0] = $info;

        return $rs;
    }		

	/**
	 * 获取多用户信息 
	 * @desc 用于获取获取多用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 排行榜列表
	 * @return string info[].utot 是否关注，0未关注，1已关注
	 * @return string info[].ttou 对方是否关注我，0未关注，1已关注
	 * @return string msg 提示信息
	 */
	public function getMultiInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$uids=checkNull($this->uids);
		$type=checkNull($this->type);
        
        $configpri=getConfigPri();
        
        if($configpri['letter_switch']!=1){
            return $rs;
        }
		
		$uids=explode(",",$uids);

		foreach ($uids as $k=>$userId) {
			if($userId){
				$userinfo= getUserInfo($userId);
				if($userinfo){
					$userinfo['utot']= isAttention($uid,$userId);
					
					$userinfo['ttou']= isAttention($userId,$uid);
					
					if($userinfo['utot']==$type){						
						$rs['info'][]=$userinfo;
					}												
				}					
			}
		}

		return $rs;
	}	

	/**
	 * 获取多用户信息(不区分是否关注)
	 * @desc 用于获取多用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 排行榜列表
	 * @return string info[].utot 是否关注，0未关注，1已关注
	 * @return string info[].ttou 对方是否关注我，0未关注，1已关注
	 * @return string msg 提示信息
	 */
	public function getUidsInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$uids=checkNull($this->uids);
		$uids=explode(",",$uids);

		foreach ($uids as $k=>$userId) {
			if($userId){
				$userinfo= getUserInfo($userId);
				if($userinfo){
					$userinfo['utot']= isAttention($uid,$userId);
					
					$userinfo['ttou']= isAttention($userId,$uid);					
                    
                    $rs['info'][]=$userinfo;
											
				}					
			}
		}

		return $rs;
	}	

	/**
	 * 登录奖励
	 * @desc 用于用户登录奖励
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].bonus_switch 登录开关，0表示未开启
	 * @return string info[0].bonus_day 登录天数,0表示已奖励
	 * @return string info[0].count_day 连续登陆天数
	 * @return string info[0].bonus_list 登录奖励列表
	 * @return string info[0].bonus_list[].day 登录天数
	 * @return string info[0].bonus_list[].coin 登录奖励
	 * @return string msg 提示信息
	 */
	public function Bonus() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		$domain = new Domain_User();
		$info=$domain->LoginBonus($uid);

		$rs['info'][0]=$info;

		return $rs;
	}		
    
	/**
	 * 登录奖励
	 * @desc 用于用户登录奖励
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].bonus_switch 登录开关，0表示未开启
	 * @return string info[0].bonus_day 登录天数,0表示已奖励
	 * @return string msg 提示信息
	 */
	public function getBonus() {
		$rs = array('code' => 0, 'msg' => T('领取成功'), 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		$domain = new Domain_User();
		$info=$domain->getLoginBonus($uid);

		if(!$info){
            $rs['code'] = 1001;
			$rs['msg'] = T('领取失败');
			return $rs;
        }
        $rs['info'][0]['score'] = $info;

		return $rs;
	}
	
	/**
	 * 设置分销上级 
	 * @desc 用于用户首次登录设置分销关系
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 提示信息
	 * @return string msg 提示信息
	 */
	public function setDistribut() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$code=checkNull($this->code);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		if($code==''){
			$rs['code']=1001;
			$rs['msg']=T('请输入邀请码');
			return $rs;
		}
		
		$domain = new Domain_User();
		$info=$domain->setDistribut($uid,$code);
		if($info==1004){
			$rs['code']=1004;
			$rs['msg']=T('已设置，不能更改');
			return $rs;
		}
        
		if($info==1002){
			$rs['code']=1002;
			$rs['msg']=T('邀请码错误');
			return $rs;
		}
        
        if($info==1003){
			$rs['code']=1003;
			$rs['msg']=T('不能填写自己下级的邀请码');
			return $rs;
		}
		
		$rs['info'][0]['msg']=T('设置成功');

		return $rs;
	}	

	/**
	 * 获取用户间印象标签 
	 * @desc 用于获取用户间印象标签
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id 标签ID
	 * @return string info[].name 名称
	 * @return string info[].colour 色值
	 * @return string info[].ifcheck 是否选择
	 * @return string msg 提示信息
	 */
	public function getUserLabel() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);
        
        $key="getUserLabel_".$uid.'_'.$touid;
		$label=getcaches($key);

		if(!$label){
            $domain = new Domain_User();
			$info = $domain->getUserLabel($uid,$touid);
            $label=$info['label'];
			setcaches($key,$label); 
		}
        
        $label_check=preg_split('/,|，/',$label);
		
        $label_check=array_filter($label_check);
        
        $label_check=array_values($label_check);
        
        
        $key2="getImpressionLabel";
		$label_list=getcaches($key2);
		if(!$label_list){
            $domain = new Domain_User();
			$label_list = $domain->getImpressionLabel();
		}
        
        foreach($label_list as $k=>$v){
            $ifcheck='0';
            if(in_array($v['id'],$label_check)){
                $ifcheck='1';
            }
            $label_list[$k]['ifcheck']=$ifcheck;
        }
        
		$rs['info']=$label_list;

		return $rs;
	}	


	/**
	 * 获取用户间印象标签 
	 * @desc 用于获取用户间印象标签
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id 标签ID
	 * @return string info[].name 名称
	 * @return string info[].colour 色值
	 * @return string msg 提示信息
	 */
	public function setUserLabel() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $touid=checkNull($this->touid);
        $labels=checkNull($this->labels);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        if($uid==$touid){
            $rs['code'] = 1003;
			$rs['msg'] = T('不能给自己设置标签');
			return $rs;
        }
        
        if($labels==''){
            $rs['code'] = 1001;
			$rs['msg'] = T('请选择印象');
			return $rs;
        }
        
        $labels_a=preg_split('/,|，/',$labels);
        $labels_a=array_filter($labels_a);
        $nums=count($labels_a);
        if($nums>3){
            $rs['code'] = 1002;
			$rs['msg'] = T('最多只能选择3个印象');
			return $rs;
        }
        

        $domain = new Domain_User();
        $result = $domain->setUserLabel($uid,$touid,$labels);

        if($result){
            $key="getUserLabel_".$uid.'_'.$touid;
            setcaches($key,$labels); 
            
            $key2="getMyLabel_".$touid;
            delcache($key2);
        }

		
		$rs['msg']=T('设置成功');

		return $rs;
	}	


	/**
	 * 获取自己所有的印象标签 
	 * @desc 用于获取自己所有的印象标签
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id 标签ID
	 * @return string info[].name 名称
	 * @return string info[].colour 色值
	 * @return string info[].nums 数量
	 * @return string msg 提示信息
	 */
	public function getMyLabel() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
    
        $key="getMyLabel_".$uid;
		$info=getcaches($key);
		
		if(!$info){
            $domain = new Domain_User();
            $info = $domain->getMyLabel($uid);
			

			setcaches($key,$info); 
		}

		$rs['info']=$info;

		return $rs;
	}	
    

	/**
	 * 获取个性设置列表 
	 * @desc 用于获取个性设置列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function getPerSetting() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_User();
//        $info = $domain->getPerSetting();

        $lang = GL();
        $info[]=array('id'=>'30','name'=>T('黑名单'),'thumb'=>'' ,'href'=>'');
        $info[]=array('id'=>'31','name'=>T('语言切换'),'thumb'=>'' ,'href'=>'');
        $info[]=array('id'=>'19','name'=>T('注销账号'),'thumb'=>'' ,'href'=>get_upload_path('/appapi/page/news?id=12&lang='.$lang));
        $info[]=array('id'=>'32','name'=>T('声音音效'),'thumb'=>'' ,'href'=>'');
        $info[]=array('id'=>'33','name'=>T('我的认证'),'thumb'=>'' ,'href'=>'');
        $info[]=array('id'=>'17','name'=>T('关于我们'),'thumb'=>'' ,'href'=>get_upload_path('/Appapi/About/index?lang='.$lang));
//        $info[]=array('id'=>'17','name'=>'意见反馈','thumb'=>'' ,'href'=>get_upload_path('/Appapi/feedback/index'));
        $info[]=array('id'=>'15','name'=>T('隐私政策'),'thumb'=>'' ,'href'=>get_upload_path('/appapi/page/news?id=4&lang='.$lang));
        $info[]=array('id'=>'16','name'=>T('当前版本'),'thumb'=>'' ,'href'=>'');
//        $info[]=array('id'=>'15','name'=>'修改密码','thumb'=>'' ,'href'=>'');
        $info[]=array('id'=>'18','name'=>T('清除缓存'),'thumb'=>'' ,'href'=>'');
//        $info[]=array('id'=>'16','name'=>'检查更新','thumb'=>'' ,'href'=>'');
        

		$rs['info']=$info;

		return $rs;
	}	

	/**
	 * 获取用户提现账号 
	 * @desc 用于获取用户提现账号
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id 账号ID
	 * @return string info[].type 账号类型
	 * @return string info[].account_bank 银行名称
	 * @return string info[].account 账号
	 * @return string info[].name 姓名
	 * @return string msg 提示信息
	 */
	public function getUserAccountList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}        
    

        $domain = new Domain_User();
        $info = $domain->getUserAccountList($uid);

		$rs['info']=$info;

		return $rs;
	}	

	/**
	 * 添加提现账号 / 修改
	 * @desc 用于添加提现账号
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function setUserAccount() {
		$rs = array('code' => 0, 'msg' => T('操作成功'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $type=checkNull($this->type);
        $account_bank=checkNull($this->account_bank);
        $account=checkNull($this->account);
        $name=checkNull($this->name);

        if($type==3){
            if($account_bank==''){
                $rs['code'] = 1001;
                $rs['msg'] = T('银行名称不能为空');
                return $rs;
            }
        }
        
        if($account==''){
            $rs['code'] = 1002;
            $rs['msg'] = T('账号不能为空');
            return $rs;
        }
        
        
        if(mb_strlen($account)>40){
            $rs['code'] = 1002;
            $rs['msg'] = T('账号长度不能超过40个字符');
            return $rs;
        }
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}        
        
        $data=array(
            'uid'=>$uid,
            'type'=>$type,
            'account_bank'=>$account_bank,
            'account'=>$account,
            'name'=>$name,
            'addtime'=>time(),
        );
        
        $domain = new Domain_User();
        $where=[
            'uid'=>$uid,
            'type'=>$type,
//            'account_bank'=>$account_bank,
//            'account'=>$account,
        ];
        $isexist=$domain->getUserAccount($where);
        if($isexist){
//            $rs['code'] = 1004;
//            $rs['msg'] = T('账号已存在');
//            return $rs;
            $result = $domain->updateUserAccount($data,$isexist[0]['id']);
            $result = $isexist[0];
        }else{
            $result = $domain->setUserAccount($data);

        }


        if(!$result){
            $rs['code'] = 1003;
            $rs['msg'] = T('添加失败，请重试');
            return $rs;
        }
        
        $rs['info'][0]=$result;

		return $rs;
	}	


	/**
	 * 删除用户提现账号 
	 * @desc 用于删除用户提现账号
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function delUserAccount() {
		$rs = array('code' => 0, 'msg' => T('删除成功'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $id=checkNull($this->id);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}        
        
        $data=array(
            'uid'=>$uid,
            'id'=>$id,
        );
        
        $domain = new Domain_User();
        $result = $domain->delUserAccount($data);

        if(!$result){
            $rs['code'] = 1003;
            $rs['msg'] = T('删除失败，请重试');
            return $rs;
        }

		return $rs;
	}	
    

    /**
     * 用户申请店铺余额提现
     * @desc 用于用户申请店铺余额提现
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setShopCash(){
    	$rs = array('code' => 0, 'msg' => T('提现成功'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);		
        $accountid=checkNull($this->accountid);		
        $money=checkNull($this->money);
        $time=checkNull($this->time);
        $sign=checkNull($this->sign);

        if($uid<0||$token==""||!$time||!$sign){
            $rs['code']=1001;
            $rs['msg']=T('参数错误');
            return $rs;
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		if(!$accountid){
            $rs['code'] = 1001;
			$rs['msg'] = T('请选择提现账号');
			return $rs;
        }

        if(!$money){
            $rs['code'] = 1002;
			$rs['msg'] = T('请输入有效的提现金额');
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
            'accountid'=>$accountid,
            'time'=>$time
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']=T('签名错误');
            return $rs; 
        }

        $configpri=getConfigPri();

        $data=array(
            'uid'=>$uid,
            'accountid'=>$accountid,
            'money'=>$money,
        );

        $domain=new Domain_User();
        $res = $domain->setShopCash($data);

        if($res==1001){
			$rs['code'] = 1001;
			$rs['msg'] = T('余额不足');
			return $rs;
		}else if($res==1004){
			$rs['code'] = 1004;
			$rs['msg'] = T('提现最低额度为{balance_cash_min}元',['balance_cash_min'=>$configpri['balance_cash_min']]);
			return $rs;
		}else if($res==1005){
			$rs['code'] = 1005;
			$rs['msg'] = T('不在提现期限内，不能提现');
			return $rs;
		}else if($res==1006){
			$rs['code'] = 1006;
			$rs['msg'] = T('每月只可提现{balance_cash_max_times}次,已达上限',['balance_cash_max_times'=>$configpri['balance_cash_max_times']]);
			return $rs;
		}else if($res==1007){
			$rs['code'] = 1007;
			$rs['msg'] = T('提现账号信息不正确');
			return $rs;
		}else if(!$res){
			$rs['code'] = 1002;
			$rs['msg'] = T('提现失败，请重试');
			return $rs;
		}
	 
		$rs['info'][0]['msg']=T('提现成功');
		return $rs;

    }

    /**
     * 获取用户的认证信息
     * @desc 用于获取用户的认证信息
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function getAuthInfo(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain=new Domain_User();
		$res=$domain->getAuthInfo($uid);
        if(empty($res)){
            $rs['code']=1001;
            $rs['msg']=T('请先进行实名认证');
            return $rs;
        }
        $res['front_view'] = get_upload_path($res['front_view']);
        $res['back_view'] = get_upload_path($res['back_view']);
        $res['handset_view'] = get_upload_path($res['handset_view']);
        if($res['status']==0){
            $rs['code']=1003;
            $rs['msg']=T('资料提交成功，审核中');
            return $rs;
        }
        if($res['status']==2){
            $rs['code']=1002;
            $rs['msg']=T('您的认证没有通过，请重新认证，原因：').$res['reason'];
            $rs['info'][0]=$res;
            return $rs;
        }

		$rs['info'][0]=$res;
		return $rs;

    }

    /**
     * 提交用户的认证信息
     * @desc 用于提交用户的认证信息
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setAuthInfo(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $real_name=checkNull($this->real_name);
        $mobile=checkNull($this->mobile);
        $cer_no=checkNull($this->cer_no);
        $front_view=checkNull($this->front_view);
        $back_view=checkNull($this->back_view);
        $handset_view=checkNull($this->handset_view);

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $data=[
            'uid'=>$uid,
            'real_name'=>$real_name,
            'mobile'=>$mobile,
            'cer_no'=>$cer_no,
            'front_view'=>$front_view,
            'back_view'=>$back_view,
            'handset_view'=>$handset_view,
            'status'=>0,
            'addtime'=>time(),
        ];

		$domain=new Domain_User();
		$res=$domain->setAuthInfo($data);

		$rs['info'][0]=$res;
		return $rs;

    }
    

    /**
     * 查看每日任务
     * @desc 用于用户查看每日任务的进度
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function seeDailyTasks(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $liveuid=checkNull($this->liveuid);
        $islive=checkNull($this->islive);
        $type=checkNull($this->type);
        if(empty($type)){
            $type = 'day';
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		
		if($islive==1){   //判断请求是否在直播间
			if($uid==$liveuid){ //主播访问
				/*观看直播计时---每日任务--取出用户进入时间*/
				$key='open_live_daily_tasks_'.$uid;
				$starttime=getcaches($key);
				if($starttime){ 
					$endtime=time();  //当前时间
					$data=[
						'type'=>'7',
						'starttime'=>$starttime,
						'endtime'=>$endtime,
					];
					dailyTasks($uid,$data);
					//删除当前存入的时间
					delcache($key);
				}	
				/*观看直播计时---用于每日任务--记录用户进入时间*/
				$enterRoom_time=time();
				setcaches($key,$enterRoom_time);
				
			}else{  //用户访问
			
				/*观看直播计时---每日任务--取出用户进入时间*/
				$key='watch_live_daily_tasks_'.$uid;
				$starttime=getcaches($key);
				if($starttime){ 
					$endtime=time();  //当前时间
					$data=[
						'type'=>'5',
						'starttime'=>$starttime,
						'endtime'=>$endtime,
					];
					dailyTasks($uid,$data);
					//删除当前存入的时间
					delcache($key);
				}	
				/*观看直播计时---用于每日任务--记录用户进入时间*/
				$enterRoom_time=time();
				setcaches($key,$enterRoom_time);

			}
		}
		
		$domain=new Domain_User();
		$info=$domain->seeDailyTasks($uid,$type);

		$configpub=getConfigPub();
		$name_coin=$configpub['name_coin']; //钻石名称

		$rs['info'][0]['tip_m']=T("温馨提示：当您某个任务达成时就会获得平台奖励给您的{name_coin}，获得的奖励需要您手动领取才可放入余额中，当日不领取次日系统会自动清零，亲爱的您一定要记得领取当日奖励哦~",['name_coin'=>$name_coin]);
		$rs['info'][0]['list']=$info;
		return $rs;

    }
	
	
	/**
     * 领取每日任务奖励
     * @desc 用于用户领取每日任务奖励
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function receiveTaskReward(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $taskid=checkNull($this->taskid);

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		$domain=new Domain_User();
		$info=$domain->receiveTaskReward($uid,$taskid);

		
		return $info;

    }

	/**
     * 获取用户是否Vip
     * @desc 获取用户是否Vip
     * @return array
     */
	public function getUserVip(){
        $rs = array('code' => 0, 'msg' => '当前用户是会员', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $uid=checkNull($this->uid);
        $domain = new Domain_User();
        $info = $domain->getUserVip($uid);
        if(!$info){
            $rs['code'] = 1;
            $rs['msg'] = T('当前用户不是会员！');
            return $rs;
        }
        return $rs;
    }

	/**
     * 获取用户邀请好友数据
     * @desc 获取用户邀请好友数据
     * @return array
     */
	public function getAgent(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $uid=checkNull($this->uid);
        $domain = new Domain_User();
        $rs['info'][0]=$domain->getAgent($uid);
        return $rs;
    }

	/**
     * 获取举报类型
     * @desc 获取举报类型
     * @return array
     */
	public function getReportUserClassify(){
        $rs = array('code' => 0, 'msg' => '获取成功', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $info = $domain->getReportUserClassify(0);
        if(!$info){
            $rs['code'] = 1;
            $rs['msg'] = T('获取失败！');
            return $rs;
        }
        $rs['info']=$info;
        return $rs;
    }

	/**
     * 举报
     * @desc 举报
     * @return array
     */
	public function report(){
        $rs = array('code' => 0, 'msg' => '举报成功', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);
        $content=checkNull($this->content);
        $reason=checkNull($this->reason);
        $image=checkNull($this->image);
        $classifyid=checkNull($this->classifyid);

        $domain = new Domain_User();
        $info = $domain->getReportUserClassify($classifyid);
        if(!$info){
            $rs['code'] = 1;
            $rs['msg'] = T('举报类型不存在！');
            return $rs;
        }

        $data=array(
            'uid'=>$uid,
            'touid'=>$touid,
            'content'=>$content,
            'reason'=>$reason,
            'image'=>$image,
            'classifyid'=>$classifyid,
            'addtime'=>time(),
            'uptime'=>time(),
        );
        $domain = new Domain_User();
        $rs = $domain->report($data);
        return $rs;
    }

    /**
     * 获取语言
     * @desc 获取语言
     * @return array
     */
    public function getLangList(){

        $domain = new Domain_User();
        $info = $domain->getLangList();
        if(!$info){
            $rs['code'] = 1;
            $rs['msg'] = T('获取语言不存在！');
            return $rs;
        }

        $rs = array('code' => 0, 'msg' => T('获取成功'), 'info' => $info);
        return $rs;
    }

    /**
     * 切换语言
     * @desc 切换语言
     * @return array
     */
    public function switchLang(){

        $lang=checkNull($this->lang);

        if (!empty($lang)) {
            SL($lang);
//            setcookie('language', $lang, time() + 86400 * 360, '/');
        }

        $rs = array('code' => 0, 'msg' => T('切换成功'), 'info' => array());
        return $rs;
    }

    /**
     * 获取用户观看记录
     * @desc 获取用户观看记录
     * @return array
     */
    public function getVideoView(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getVideoView($uid,$p);
        return $rs;
    }

    /**
     * 批量删除用户观看记录
     * @desc 批量删除用户观看记录
     * @return array
     */
    public function delVideoView(){
        $rs = array('code' => 0, 'msg' => T('删除成功'), 'info' => array());

        $ids=checkNull($this->ids);
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->delVideoView($uid,$ids);
        return $rs;
    }

    /**
     * 检查用户是否开启了青少年模式
     * @desc 检查用户是否开启了青少年模式
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info[0].is_setpassword 是否设置过密码 0 否 1 是
     * @return array info[0].status 是否开启青少年模式 0 否 1 是
     * @return array info[0].is_tip 是否提示用户弹窗显示青少年模式下不能继续使用app   0 否  1 是
     * @return array info[0].tips  弹窗显示青少年模式下不能继续使用app的提示语
     * @return array info[0].teenager_des  青少年模式提示语
     */
    public function checkTeenager(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $res = $domain->checkTeenager($uid);

        $configpub=getConfigPub();

        $res['info'][0]['is_tip']='0';
        $res['info'][0]['tips']='';
        $res['info'][0]['teenager_des']=$configpub['teenager_des'];

        //开启了青少年模式
        if($res['info'][0]['is_setpassword'] && $res['info'][0]['status']){
            $overtime = $domain->checkTeenagerIsOvertime($uid);

            if($overtime['code']!=0){
                $res['info'][0]['is_tip']='1';
                $res['info'][0]['tips']=$overtime['msg'];
            }
        }

        return $res;
    }

    /**
     * 用户开启青少年模式/初次设置密码后重新设置密码
     * @desc 用户开启青少年模式/初次设置密码后重新设置密码
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setTeenagerPassword(){
        $rs = array('code' => 0, 'msg' => T('青少年模式开启成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $password=checkNull($this->password);
        $type=checkNull($this->type);

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if(mb_strlen($password)!=4){
            $rs['code'] = 1001;
            $rs['msg'] =T('密码必须为{num}位',['num'=>4]);
            return $rs;
        }

        $domain=new Domain_User();
        $res=$domain->setTeenagerPassword($uid,$password,$type);

        if($res==1001){
            $rs['code'] = 1002;
            $rs['msg'] =T('密码错误');
            return $rs;
        }

        if($res==1002){
            $rs['code'] = 1003;
            $rs['msg'] =T('密码设置失败,请稍后重试');
            return $rs;
        }

        return $rs;
    }

    /**
     * 用户修改青少年模式密码
     * @desc 用户修改青少年模式密码
     * @return int code 状态码,0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function updateTeenagerPassword(){
        $rs = array('code' => 0, 'msg' => T('密码修改成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $oldpassword=checkNull($this->oldpassword);
        $password=checkNull($this->password);

        if(!$oldpassword){
            $rs['code']=1001;
            $rs['msg']=T('请输入原密码');
            return $rs;
        }

        if(mb_strlen($oldpassword)!=4){
            $rs['code']=1002;
            $rs['msg']=T('原密码长度必须为{num}位',['num'=>4]);
            return $rs;
        }

        if(!$password){
            $rs['code']=1001;
            $rs['msg']=T('请输入新密码');
            return $rs;
        }

        if(mb_strlen($password)!=4){
            $rs['code']=1002;
            $rs['msg']=T('新密码长度必须为4位');
            return $rs;
        }

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $res=$domain->updateTeenagerPassword($uid,$oldpassword,$password);

        if($res==1001){
            $rs['code']=1003;
            $rs['msg']=T('你还未设置密码');
            return $rs;
        }

        if($res==1002){
            $rs['code']=1004;
            $rs['msg']=T('原密码错误');
            return $rs;
        }

        if(!$res){
            $rs['code']=1005;
            $rs['msg']=T('密码修改失败');
            return $rs;
        }

        return $rs;
    }

    /**
     * 用户关闭青少年模式
     * @desc 用户关闭青少年模式
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function closeTeenager(){

        $rs = array('code' => 0, 'msg' => T('青少年模式关闭成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $password=checkNull($this->password);

        if(!$password){
            $rs['code']=1001;
            $rs['msg']=T('请输入密码');
            return $rs;
        }

        if(mb_strlen($password)!=4){
            $rs['code']=1002;
            $rs['msg']=T('密码长度必须为{num}位',['num'=>4]);
            return $rs;
        }

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $res=$domain->closeTeenager($uid,$password);
        if($res==1001){
            $rs['code'] = 1003;
            $rs['msg'] = T('你还未开启青少年模式');
            return $rs;
        }

        if($res==1002){
            $rs['code'] = 1004;
            $rs['msg'] = T('青少年模式未开启');
            return $rs;
        }

        if($res==1003){
            $rs['code'] = 1005;
            $rs['msg'] = T('密码错误');
            return $rs;
        }

        if(!$res){
            $rs['code']=1006;
            $rs['msg']=T('青少年模式关闭失败');
            return $rs;
        }

        return $rs;
    }

    /**
     * 定时增加用户使用青少年模式时间
     * @desc 定时增加用户使用青少年模式时间
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function addTeenagerTime(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_User();
        $res=$domain->addTeenagerTime($uid);

        return $res;
    }


    /**
     * 更换个人中心背景图
     * @desc 更换个人中心背景图
     * @retun int code 状态码,0表示成功
     * @retun string msg 返回信息
     * @retun array info 返回信息
     * @retun array info[0]['bg_img'] 返回上传的背景图
     * */
    public function updateBgImg(){
        $rs = array('code' => 0, 'msg' => T('背景图更换成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $img=checkNull($this->img);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if(!$img){
            $rs['code']=1001;
            $rs['msg']=T('请上传背景图');
            return $rs;
        }

        $domain=new Domain_User();
        $res=$domain->updateBgImg($uid,$img);

        if($res==1001){
            $rs['code']=1002;
            $rs['msg']=T('背景图更换失败');
            return $rs;
        }

        $userinfo=getUserInfo($uid);
        $rs['info'][0]['bg_img']=$userinfo['bg_img'];
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

    /**
     * 获取用户充值明细
     * @desc 获取用户充值明细
     * @return array
     */
    public function getChangeUserList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);
        $source=checkNull($this->source);
        if(empty($source)){
            $source='coin';
        }

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getChangeUserList($uid,$p,$source);
        return $rs;
    }

    /**
     * 获取用户USDT充值明细
     * @desc 获取用户USDT充值明细
     * @return array
     */
    public function getChangeUserUsdtList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getChangeUserUsdtList($uid,$p);
        return $rs;
    }

    /**
     * 获取用户收益明细
     * @desc 获取用户收益明细
     * @return array
     */
    public function getEarningsList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getEarningsList($uid,$p);
        return $rs;
    }

    /**
     * 获取用户提现明细
     * @desc 获取用户提现明细
     * @return array
     */
    public function getCashList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getCashList($uid,$p);
        return $rs;
    }

    /**
     * 获取用户提现USDT明细
     * @desc 获取用户提现USDT明细
     * @return array
     */
    public function getUsdtList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getUsdtList($uid,$p);
        return $rs;
    }

    /**
     * 获取用户兑换页数据
     * @desc 获取用户兑换页数据
     * @return array
     */
    public function getConversionInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getConversionInfo($uid);
        return $rs;
    }

    /**
     * 用户兑换操作
     * @desc 用户兑换操作
     * @return array
     */
    public function setConversion(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $conversion_source=checkNull($this->conversion_source);
        $conversion_location=checkNull($this->conversion_location);
        $number=checkNull($this->number);
        $timestamp=checkNull($this->timestamp);
        $nonce=checkNull($this->nonce);
        $sign=checkNull($this->sign);

        $checkdata=array(
            'uid'=>$uid,
            'number'=>$number,
            'conversion_source'=>$conversion_source,
            'conversion_location'=>$conversion_location,
            'timestamp'=>$timestamp,
            'nonce'=>$nonce,
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']=T('签名错误');
            $rs['getsign']=getSignUrl($checkdata);
            return $rs;
        }

        $key = 'getNonce_'.$uid.'_'.$nonce;
        $get_nonce = getcaches($key);
        if ($get_nonce) {
            $rs['code']=1001;
            $rs['msg']=T('非法操作');
            return $rs;
        }else{
            setcaches($key,1,300);
        }

        $now = time();
        $timestamp = (int)$timestamp+300;
        if($now>$timestamp){
            $rs['code']=1001;
            $rs['msg']=T('非法操作');
            return $rs;
        }

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $capital= DI()->config->get('app.Capital');
        if(!in_array($conversion_source,$capital)){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换来源错误');
            return $rs;
        }
        if($conversion_source=='coin'){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换来源错误');
            return $rs;
        }
        if(!in_array($conversion_location,$capital)){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换去处错误');
            return $rs;
        }
        if($conversion_source=='popo'&&!in_array($conversion_location, ['usdt', 'coin'])){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换去处错误');
            return $rs;
        }
        if($conversion_source=='lala'&&!in_array($conversion_location, ['usdt', 'coin'])){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换去处错误');
            return $rs;
        }
        if($conversion_source=='usdt'&&!in_array($conversion_location, ['popo', 'coin'])){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换去处错误');
            return $rs;
        }
        if($conversion_source==$conversion_location){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换来源于兑换去处不可一样');
            return $rs;
        }
        if($number<0.001&&$conversion_source=='usdt'){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换数量不可以小于0.001');
            return $rs;
        }
        if(strpos($number, '.') !== false&&$conversion_source!='usdt'){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换数量必须为整数');
            return $rs;
        }

        $domain = new Domain_User();
        $info = $domain->setConversion($uid,$conversion_source,$conversion_location,$number);
        if($info==1002){
            $rs['code'] = 1002;
            $rs['msg'] = T('兑换失败，请重试');
            return $rs;
        }else if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = T('兑换数量不可大于可兑换数量');
            return $rs;
        }

        if(isset($info['code'])&&$info['code']==400){
            return $info;
        }

        $rs['info'][0]['msg']=T('兑换成功');
        return $rs;
    }

    /**
     * 获取用户兑换记录
     * @desc 获取用户兑换记录
     * @return array
     */
    public function getConversionList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $type=checkNull($this->type);
        $p=checkNull($this->p);

        $capital= DI()->config->get('app.Capital');
        if(!in_array($type,$capital)){
            $rs['code'] = 400;
            $rs['msg'] = T('兑换类型错误');
            return $rs;
        }

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getConversionList($uid,$type,$p);
        return $rs;
    }

    /**
     * 打赏挖矿页基本信息
     * @desc 获取打赏挖矿页基本信息
     * @return array
     */
    public function getMineMachineInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getMineMachineInfo($uid);
        return $rs;
    }

    /**
     * POPO收益数据获取
     * @desc POPO收益数据获取
     * @return array
     */
    public function getPopoInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getPopoInfo($uid);
        return $rs;
    }

    /**
     * 获取矿机等级
     * @desc 获取用户兑换记录
     * @return array
     */
    public function getMineMachineList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getMineMachineList($uid);
        return $rs;
    }

    /**
     * 获取我的算力矿机
     * @desc 获取用户兑换记录
     * @return array
     */
    public function getMyMineMachineList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getMyMineMachineList($uid,$p);
        return $rs;
    }

    /**
     * 获取赏金分红统计数据
     * @desc 获取赏金分红统计数据
     * @return array
     */
    public function getMyMineMachineDividend(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getMyMineMachineDividend($uid);
        return $rs;
    }

    /**
     * 获取钻石打赏明细
     * @desc 获取打赏轮播列表
     * @return array
     */
    public function getMyCoinRewardList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getMyCoinRewardList($uid,$p);
        return $rs;
    }

    /**
     * 获取打赏轮播
     * @desc 获取打赏轮播列表
     * @return array
     */
    public function getMyMineMachineRewardList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getMyMineMachineRewardList($uid,$p);
        return $rs;
    }

    /**
     * 划转POPO分红池到流通POPO
     * @desc 划转POPO分红池到流通POPO
     * @return array
     */
    public function setTransferPoPoDividendToCurrency(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $number=checkNull($this->number);
        $timestamp=checkNull($this->timestamp);
        $nonce=checkNull($this->nonce);
        $sign=checkNull($this->sign);

        $checkdata=array(
            'uid'=>$uid,
            'number'=>$number,
            'timestamp'=>$timestamp,
            'nonce'=>$nonce,
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']=T('签名错误');
            return $rs;
        }

        $key = 'getNonce_'.$uid.'_'.$nonce;
        $get_nonce = getcaches($key);
        if ($get_nonce) {
            $rs['code']=1001;
            $rs['msg']=T('非法操作');
            return $rs;
        }else{
            setcaches($key,1,300);
        }

        $now = time();
        $timestamp = (int)$timestamp+300;
        if($now>$timestamp){
            $rs['code']=1001;
            $rs['msg']=T('非法操作');
            return $rs;
        }

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        if($number<1||strpos($number, '.') !== false){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('划转数量至少为1，并且为整数');
            return $rs;
        }
        $domain = new Domain_User();
        $info = $domain->setTransferPoPoDividendToCurrency($uid,$number);
        if($info==1011){
            $rs['code'] = 1002;
            $rs['msg'] = T('可划转数量不足');
            return $rs;
        }elseif ($info==1002) {
            $rs['code'] = 1002;
            $rs['msg'] = T('划转失败');
            return $rs;
        }

        if(isset($info['code'])&&$info['code']==400){
            return $info;
        }

        $rs['info'][0]['msg']=T('划转成功');
        return $rs;
    }

    /**
     * 获取POPO分红池明细
     * @desc 获取POPO分红池明细
     * @return array
     */
    public function getPoPoDividendList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getPoPoDividendList($uid,$p);
        return $rs;
    }

    /**
     * 获取LALA收益明细
     * @desc 获取LALA收益明细
     * @return array
     */
    public function getLalaList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getLalaList($uid,$p);
        return $rs;
    }

    /**
     * 获取万能积分明细
     * @desc 获取万能积分明细
     * @return array
     */
    public function getScoreList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'] = $domain->getScoreList($uid,$p);
        return $rs;
    }

    /**
     * 获取万能积分页面数据
     * @desc 获取万能积分页面数据
     * @return array
     */
    public function getScoreInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'][0] = $domain->getScoreInfo($uid);
        return $rs;
    }

    /**
     * 获取万能积分收益页面数据
     * @desc 获取万能积分页面数据
     * @return array
     */
    public function getScoreEarningsInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'][0] = $domain->getScoreEarningsInfo($uid);
        return $rs;
    }

    /**
     * 获取我要合作页面信息
     * @desc 获取打赏轮播列表
     * @return array
     */
    public function getMyCooperation(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $domain = new Domain_User();
        $rs['info'][0] = $domain->getMyCooperation($uid);
        return $rs;
    }
}
