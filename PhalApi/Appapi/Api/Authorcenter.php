<?php
/**
 * 创作者中心
 */
class Api_Authorcenter extends PhalApi_Api {

	public function getRules() {
		return array(
            'getAuthorCenterList' => array(
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),

            'getCollectAuthorCenterList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),

			'getAuthorCenterInfo'=>array(
                'author_center_id' => array('name' => 'author_center_id', 'type' => 'int', 'require' => true, 'default'=>'1' ,'desc' => '活动ID'),
                'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '用户ID'),
			),

			'getVideoList'=>array(
                'author_center_id' => array('name' => 'author_center_id', 'require' => true, 'type' => 'int', 'default'=>'1' ,'desc' => '活动ID'),
                'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '用户ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '1 我投稿的 2 示例视频'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),

			'getContributeVideoList'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
                'sort' => array('name' => 'sort', 'type' => 'string', 'desc' => '时间倒序 addtime DESC 时间正序 addtime ASC （同理 likes 点赞 comments 评论 shares 分享）'),
                'day' => array('name' => 'day', 'type' => 'int', 'desc' => '7 , 30 , 60 , 90（天）'),
			),

			'collectAuthorCenter'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => 'Token'),
                'author_center_id' => array('name' => 'author_center_id', 'type' => 'int', 'desc' => '活动ID'),
			),

			'businessData'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'1', 'require' => true ,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => 'Token'),
			),
		);
	}
	

	/**
	 * 创作者中心列表
	 * @desc 用于 获取创作者中心列表
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getAuthorCenterList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);

		$domain = new Domain_Authorcenter();
        $rs['info'] = $domain->getAuthorCenterList($p);

        foreach($rs['info'] as $k=>$v){
            $v['active_start_time_md']=date('m月d日',$v['active_start_time']);
            $v['active_end_time_md']=date('m月d日',$v['active_end_time']);
            $v['active_start_time']=date('Y-m-d',$v['active_start_time']);
            $v['active_end_time']=date('Y-m-d',$v['active_end_time']);
            $rs['info'][$k]=$v;
        }

		return $rs;
	}


	/**
	 * 创作者中心收藏列表
	 * @desc 用于 获取创作者中心收藏列表
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getCollectAuthorCenterList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=checkNull($this->p);
        $uid=checkNull($this->uid);

		$domain = new Domain_Authorcenter();
        $rs['info'] = $domain->getCollectAuthorCenterList($p, $uid);

        foreach($rs['info'] as $k=>$v){
            $v['active_start_time_md']=date('m月d日',$v['active_start_time']);
            $v['active_end_time_md']=date('m月d日',$v['active_end_time']);
            $v['active_start_time']=date('Y-m-d',$v['active_start_time']);
            $v['active_end_time']=date('Y-m-d',$v['active_end_time']);
            $rs['info'][$k]=$v;
        }

		return $rs;
	}


	/**
	 * 创作者中心详情
	 * @desc 用于 获取创作者中心详情
	 * @return int code 操作码，0表示成功
	 * @return array info
     * @return string info.iscollect 是否收藏 0 否 1 是
     * @return string info.hot 热度
	 * @return string msg 提示信息
	 */
	public function getAuthorCenterInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $author_center_id=checkNull($this->author_center_id);
        $uid=checkNull($this->uid);

		$domain = new Domain_Authorcenter();
        $rs['info'] = $domain->getAuthorCenterInfo($author_center_id,$uid);

        $rs['info']['active_start_time']=date('m.d',$rs['info']['active_start_time']);
        $rs['info']['active_end_time']=date('m.d',$rs['info']['active_end_time']);
        $rs['info']['submission_start_time']=date('m.d',$rs['info']['submission_start_time']);
        $rs['info']['submission_end_time']=date('m.d',$rs['info']['submission_end_time']);

		return $rs;
	}


	/**
	 * 七日经营数据
	 * @desc 用于 获取七日经营数据
	 * @return int code 操作码，0表示成功
	 * @return array info
     * @return string info.contribute_count 投稿作品数
     * @return string info.views 播放量
     * @return string info.likes 点赞数
     * @return string info.percent 完播数
     * @return string info.fans_count 总粉丝数
     * @return string info.add_fans_count 粉丝净增数
     * @return string info.cancel_fans_count 取关粉丝数
     * @return string info.sex_percent 男女比例
	 * @return string msg 提示信息
	 */
	public function businessData() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

		$domain = new Domain_Authorcenter();
        $rs['info'] = $domain->getBusinessData($uid);

		return $rs;
	}

    /**
     * 获取我投稿的，示例视频
     * @desc 用于获取我投稿的，示例视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return object info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string info[].isattent 是否关注
     * @return string info[].thumb_s 封面小图，分享用
     * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string info[].goodsid 商品ID，0为无商品
     * @return object info[].goodsinfo 商品信息
     * @return string info[].goodsinfo.name 名称
     * @return string info[].goodsinfo.href 链接
     * @return string info[].goodsinfo.thumb 图片
     * @return string info[].goodsinfo.old_price 原价
     * @return string info[].goodsinfo.price 现价
     * @return string info[].goodsinfo.des 介绍
     * @return string msg 提示信息
     */
    public function getVideoList() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $type=checkNull($this->type);
        $p=checkNull($this->p);
        $author_center_id=checkNull($this->author_center_id);

        $domain = new Domain_Authorcenter();
        $info= $domain->getVideoList($author_center_id, $uid,$p,$type);

        if($info==10010){
            $rs['code'] = 0;
            $rs['msg'] = T("暂无视频列表");
            return $rs;
        }


        $rs['info'] =$info;
        return $rs;
    }

    /**
     * 获取投稿列表
     * @desc 用于获取投稿列表
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return object info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string info[].isattent 是否关注
     * @return string info[].thumb_s 封面小图，分享用
     * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string info[].goodsid 商品ID，0为无商品
     * @return object info[].goodsinfo 商品信息
     * @return string info[].goodsinfo.name 名称
     * @return string info[].goodsinfo.href 链接
     * @return string info[].goodsinfo.thumb 图片
     * @return string info[].goodsinfo.old_price 原价
     * @return string info[].goodsinfo.price 现价
     * @return string info[].goodsinfo.des 介绍
     * @return string msg 提示信息
     */
    public function getContributeVideoList() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $sort=checkNull($this->sort);
        $day=checkNull($this->day);
        $p=checkNull($this->p);

        $domain = new Domain_Authorcenter();
        $info= $domain->getContributeVideoList($uid,$p,$sort,$day);

        if($info==10010){
            $rs['code'] = 0;
            $rs['msg'] = T("暂无视频列表");
            return $rs;
        }


        $rs['info'] =$info;
        return $rs;
    }

    /**
     * 收藏活动/取消收藏
     * @desc 用于收藏活动/取消收藏
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function collectAuthorCenter(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $author_center_id=checkNull($this->author_center_id);

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain=new Domain_Authorcenter();
        $res=$domain->collectAuthorCenter($uid,$author_center_id);

        if($res==1001){
            $rs['code']=1001;
            $rs['msg']=T('该音乐已下架');
            return $rs;
        }

        if($res==200){
            $rs['msg']=T("取消收藏成功");
            $rs['info'][0]['iscollect']=0;
            return $rs;
        }

        if($res==201){
            $rs['code']=1002;
            $rs['msg']=T("取消收藏失败");
            return $rs;
        }

        if($res==300){
            $rs['msg']=T("收藏成功");
            $rs['info'][0]['iscollect']=1;
            return $rs;
        }

        if($res==301){
            $rs['code']=1002;
            $rs['msg']=T("收藏失败");
            return $rs;
        }


    }
}
