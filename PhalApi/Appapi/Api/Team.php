<?php
/**
 * 我的团队
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Api_Team extends PhalApi_Api {

	public function getRules() {
        return array(
            'getMyInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true,'desc' => '用户id'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),
            'getMyTeamLists'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'isauth'=>array('name'=>'isauth','type' => 'int','desc' => '1 已实名， 0未实名'),
                'sort' => array('name' => 'sort', 'type' => 'string', 'desc' => '时间倒序 addtime DESC 时间正序 addtime ASC'),
                'key'=>array('name'=>'key','type' => 'int', 'desc' => '用户名或者手机号'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
        );
	}
	
	/**
     * 获取我的团队页面我的基本信息
     * @desc 用于获取我的团队页面我的基本信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info
     */
    
    public function getMyInfo(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain=new Domain_Team();
        $res=$domain->getMyInfo($uid);

        $rs['info']=$res;

        return $rs;

    }

    /**
     * 获取我的直推用户数据
     * @desc 获取我的直推用户数据
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info
     */
    public function getMyTeamLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $isauth=checkNull($this->isauth);
        $sort=checkNull($this->sort);
        $key=checkNull($this->key);
        $p=checkNull($this->p);
        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        if($sort&&!in_array($sort, ['addtime DESC', 'addtime ASC'])){
            $rs['code'] = 400;
            $rs['msg'] = T('参数错误');
            return $rs;
        }

        $domain=new Domain_Team();
        $info=$domain->getMyTeamLists($uid,$p,$isauth,$sort,$key);
        $rs['info']=$info;

        return $rs;

    }

} 
