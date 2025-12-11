<?php
/**
 * 官方通知
 */
if (!session_id()) session_start();
class Api_Official extends PhalApi_Api {

	public function getRules() {
		return array(
            'getOfficialNews' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
			),
            'getOfficialList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'require' => false, 'desc' => '翻页数'),
			),
            'getOfficialInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'id' => array('name' => 'id', 'type' => 'int', 'min' => 1, 'require' => false, 'desc' => '详情ID'),
            ),
		);
	}

	/**
	 * 通知最新情况
	 * @desc 用于获取通知最新情况
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getOfficialNews() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        
        $domain = new Domain_Official();
        $info = $domain->getOfficialNews();

        $info['pushtime']=date('m-d H:i',$info['pushtime']);
        $info['thumb']=get_upload_path('');

		$rs['info']=$info;

		return $rs;
	}

	/**
	 * 通知列表
	 * @desc 用于获取官方通知列表
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getOfficialList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $p=checkNull($this->p);

        $domain = new Domain_Official();
        $info = $domain->getOfficialList($p);

        foreach($info as $k=>$v){
            $v['pushtime']=datetime($v['pushtime']);
            $v['thumb']=get_upload_path('');
            $info[$k]=$v;
        }

		$rs['info']=$info;

		return $rs;
	}

	  /**
     * 通知详情
     * @desc 用于获取官方通知详情
     * @return array
     */
	public function getOfficialInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $checkToken=checkToken($this->uid,$this->token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $id=checkNull($this->id);

        $domain = new Domain_Official();
        $rs['info'] = $domain->getOfficialInfo($id);
        if(!$rs['info']){
            $rs['code'] = 1;
            $rs['msg'] = T('信息不存在！');
            return $rs;
        }

        return $rs;
    }
    
}
