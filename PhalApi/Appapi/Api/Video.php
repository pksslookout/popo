<?php
/**
 * 短视频
 */
//if (!session_id()) session_start();
class Api_Video extends PhalApi_Api {

	public function getRules() {
		return array(

            'getCon' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
			),
            
			'setVideo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'title' => array('name' => 'title', 'type' => 'string',  'desc' => '标题'),
				'thumb' => array('name' => 'thumb', 'type' => 'string',  'require' => true, 'desc' => '封面图'),
				'href' => array('name' => 'href', 'type' => 'string',  'require' => true, 'desc' => '视频链接'),
				'href_w' => array('name' => 'href_w', 'type' => 'string',   'desc' => '水印视频链接'), 
				'lat' => array('name' => 'lat', 'type' => 'string',  'desc' => '维度'),
				'lng' => array('name' => 'lng', 'type' => 'string',  'desc' => '经度'),
				'city' => array('name' => 'city', 'type' => 'string',  'desc' => '城市'),
				'music_id' => array('name' => 'music_id', 'type' => 'int','default'=>0, 'desc' => '背景音乐id'),
                'type' => array('name' => 'type', 'type' => 'int','default'=>0, 'desc' => '绑定的内容类型 0 没绑定 1 自己商品 2 付费内容 3代售商品'),
                'goodsid' => array('name' => 'goodsid', 'type' => 'int','default'=>0, 'desc' => '商品ID'),
                'author_center_id' => array('name' => 'author_center_id', 'type' => 'int','default'=>0, 'desc' => '关联创作者活动ID（活动投稿带上）'),
                'classid' => array('name' => 'classid', 'type' => 'int','default'=>0, 'require' => false, 'desc' => '视频分类ID'),
                'coin' => array('name' => 'coin', 'type' => 'int','default'=>0, 'require' => false, 'desc' => '收取金额'),
                'is_ad' => array('name' => 'is_ad', 'type' => 'int','default'=>0, 'require' => false, 'desc' => '是否为广告视频 0 否 1 是'),
                'dynamic_label_id' => array('name' => 'dynamic_label_id', 'type' => 'int','default'=>0, 'require' => false, 'desc' => '关联话题ID'),
//				'ad_endtime' => array('name' => 'ad_endtime', 'type' => 'string', 'default'=>'','desc' => '广告显示到期时间 2025-06-25'),
				'ad_url' => array('name' => 'ad_url', 'type' => 'string', 'default'=>'','desc' => '广告外链'),
				'anyway' => array('name' => 'anyway', 'type' => 'string', 'default'=>'1.1','desc' => '横竖屏(封面-高/宽)，大于1表示竖屏,小于1表示横屏'),
                'is_popular' => array('name' => 'is_popular', 'type' => 'int', 'default'=>'0','desc' => '是否上热门'),
                'price'=>array('name' => 'price', 'type' => 'int', 'min' => 100, 'desc' => '投放金额'),
                'duration'=>array('name' => 'duration', 'type' => 'int', 'min' => 6, 'desc' => '投放时长'),
                'timestamp' => array('name' => 'timestamp', 'type' => 'string', 'desc' => '秒级时间戳'),
                'nonce' => array('name' => 'nonce', 'type' => 'string', 'desc' => '8位随机数（包含字母数字）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'default'=>'', 'desc' => '签名(uid+price+timestamp+nonce)'),
			),
            'setComment' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'default'=>0, 'desc' => '回复的评论UID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论commentid'),
                'parentid' => array('name' => 'parentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论ID'),
                'content' => array('name' => 'content', 'type' => 'string',  'default'=>'', 'desc' => '内容'),
                'at_info'=>array('name'=>'at_info','type'=>'string','desc'=>'被@的用户json信息 格式如下：[{"uid":"43595", "user_nicename":"手机用户9535"},{"uid":"43595", "user_nicename":"手机用户9535"}]'),
            ),
            'addView' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),
            ),
            'upPopular' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'price'=>array('name' => 'price', 'type' => 'int', 'min' => 100, 'require' => true, 'desc' => '投放金额'),
                'duration'=>array('name' => 'duration', 'type' => 'int', 'min' => 6, 'require' => true, 'desc' => '投放时长'),
                'timestamp' => array('name' => 'timestamp', 'type' => 'string', 'require' => true, 'desc' => '秒级时间戳'),
                'nonce' => array('name' => 'nonce', 'type' => 'string', 'require' => true, 'desc' => '8位随机数（包含字母数字）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'require' => true, 'default'=>'', 'desc' => '签名(uid+videoid+price+timestamp+nonce)'),
            ),
            'getPopularRule' => array(
            ),
            'addLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
            'addRecommend' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
            'setUnconcern' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			'addStep' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			
			'addShare' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),
            ),
			
			'setBlack' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),

			'setVideoCoin' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			
			'addCommentLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论/回复 ID'),
            ),
            'getVideoList' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'getAttentionVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'getAdVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'collectVideo'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'videoid'=>array('name'=>'videoid','type' => 'int','require' => true,'desc' => '视频id'),
            ),

            'getGiftList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'sendGift' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'videoid' => array('name' => 'videoid', 'type' => 'string', 'require' => true, 'desc' => '视频ID'),
                'giftid' => array('name' => 'giftid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物ID'),
                'giftcount' => array('name' => 'giftcount', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物数量'),
                'ispack' => array('name' => 'ispack', 'type' => 'int', 'default'=>'0', 'desc' => '是否背包'),
                'is_sticker' => array('name' => 'is_sticker', 'type' => 'int', 'default'=>'0', 'desc' => '是否为贴纸礼物：0：否；1：是'),
                'timestamp' => array('name' => 'timestamp', 'type' => 'string', 'require' => true, 'desc' => '秒级时间戳'),
                'nonce' => array('name' => 'nonce', 'type' => 'string', 'require' => true, 'desc' => '8位随机数（包含字母数字）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'require' => true, 'default'=>'', 'desc' => '签名(uid+videoid+giftid+giftcount+timestamp+nonce)'),
            ),

            'getVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
            'getComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getReplys' => array(
				'uid' => array('name' => 'uid', 'type' => 'int',  'require' => true, 'desc' => '用户ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getMyVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

            'del' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			
			'report' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => 'token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
            ),

			'getHomeCollectVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => 'token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getHomeVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

			'getHomeLikeVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

			'getCreateNonreusableSignature' => array(
                'imgname' => array('name' => 'imgname', 'type' => 'string', 'desc' => '图片名称'),
                'videoname' => array('name' => 'videoname', 'type' => 'string', 'desc' => '视频名称'),
				'folderimg' => array('name' => 'folderimg', 'type' => 'string','desc' => '图片文件夹'),
				'foldervideo' => array('name' => 'foldervideo', 'type' => 'string', 'desc' => '视频文件夹'),
            ),


            'getRecommendVideos'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            	'isstart' => array('name' => 'isstart', 'type' => 'int', 'default'=>0, 'desc' => '是否启动App'),
            ),

            'getNearby'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'setConversion'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),
            ),
			
			'getClassVideo'=>array(
                'videoclassid' => array('name' => 'videoclassid', 'type' => 'int', 'default'=>'0' ,'desc' => '视频分类ID'),
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

			'getMusicVideo'=>array(
                'music_id' => array('name' => 'music_id', 'type' => 'int', 'default'=>'0' ,'desc' => '音乐ID'),
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

			'getDynamicLabelVideo'=>array(
                'dynamic_label_id' => array('name' => 'dynamic_label_id', 'type' => 'int', 'default'=>'0' ,'desc' => '话题ID'),
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
			
			'startWatchVideo'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),
			
			'endWatchVideo'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),

			'delComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'commentuid' => array('name' => 'commentuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论者用户ID'),
                
            ),

			'getVideoWatermark' => array(
                'href' => array('name' => 'href', 'type' => 'string',  'require' => true, 'desc' => '视频链接'),
            ),

		);
	}
    
    /**
	 * 获取视频配置
	 * @desc 用于获取视频配置
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isshop 是否有店铺，0否1是
	 * @return string msg 提示信息
	 */
	public function getCon() { 
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        
        $isshop=1;

        // 店铺是否开通
		$is_shop = checkShopIsPass($uid);
		//付费内容是否开通
		$is_paidprogram=checkPaidProgramIsPass($uid);

		if(!$isshop && !$is_paidprogram){
			$isshop=0;
		}
        
        $cdnset['isshop']=$isshop;
        
		$rs['info'][0]=$cdnset;


		return $rs;
	}

    /**
	 * 获取水印视频链接
	 * @desc 用于获取水印视频链接
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function getVideoWatermark() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        $href=checkNull($this->href);
        $curlPost['href'] = $href;
        $re = curlPost($curlPost,get_upload_path('/appapi/video/watermark'));
        $re = json_decode($re,true);
        if($re['ret']==200){
            $rs['info'][0]['href_w']=$re['data']['url'];
        }else{
            $rs['info'][0]['href_w']=$re['data']['url'];
            $rs['msg']=$re['msg'];

        }
        return $rs;
	}

    /**
     * 收藏视频/取消收藏
     * @desc 用于收藏视频/取消收藏
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function collectVideo(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 10020;
            $rs['msg'] = T('该账号已被禁用');
            return $rs;
        }



        $domain=new Domain_Video();
        $res=$domain->collectVideo($uid,$videoid);

        if($res==1001){
            $rs['code']=1001;
            $rs['msg']=T('该视频已下架');
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

    /**
     * 用户分享视频
     * @desc 用于每日任务统计分享次数
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function shareVideo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        $data=[
            'type'=>'10',
            'nums'=>'1',

        ];
        dailyTasks($uid,$data);

        return $rs;
    }

	/**
	 * 发布短视频
	 * @desc 用于发布短视频
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].id 视频记录ID
	 * @return string msg 提示信息
	 */
	public function setVideo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$title=checkNull($this->title);
		$thumb=checkNull($this->thumb);
		$href=checkNull($this->href);
		$href_w=checkNull($this->href_w);
		$lat=checkNull($this->lat);
		$lng=checkNull($this->lng);
		$city=checkNull($this->city);
		$music_id=checkNull($this->music_id);
        $type=checkNull($this->type);
        $goodsid=checkNull($this->goodsid);
        $author_center_id=checkNull($this->author_center_id);
        $classid=checkNull($this->classid);//视频分类ID
		$anyway=checkNull($this->anyway);
		$coin=checkNull($this->coin);
		$is_ad=checkNull($this->is_ad);
		$dynamic_label_id=checkNull($this->dynamic_label_id);
//		$ad_endtime=checkNull($this->ad_endtime);
		$ad_url=checkNull($this->ad_url);
        $is_popular=checkNull($this->is_popular);
        $price=checkNull($this->price);
        $duration=checkNull($this->duration);
        $timestamp=checkNull($this->timestamp);
        $nonce=checkNull($this->nonce);
        $sign=checkNull($this->sign);

//        if($classid<1){
//            $rs['code'] = 10012;
//			$rs['msg'] = T('请选择分类');
//			return $rs;
//        }
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
		$sensitivewords=sensitiveField($title);
		if($sensitivewords==1001){
			$rs['code'] = 10011;
			$rs['msg'] = T('输入非法，请重新输入');
			return $rs;
		}
		
        $thumb_s='';
        if($thumb){
            $configpri = getConfigPri();
            $cloudtype=$configpri['cloudtype'];
            if($cloudtype==1){
                $thumb_s=$thumb.'?imageView2/2/w/200/h/200';
            }else{
                $thumb_s=$thumb.'?imageMogr2/crop/200x200/gravity/center';
            }
        }
		

		$data=array(
			"uid"=>$uid,
			"title"=>$title,
			"thumb"=>$thumb,
			"thumb_s"=>$thumb_s,
			"href"=>$href,
			"href_w"=>$href_w,
			"lat"=>$lat,
			"lng"=>$lng,
			"city"=>$city,
			"likes"=>0,
			"views"=>1, //因为涉及到推荐排序问题，所以初始值要为1
			"comments"=>0,
			"addtime"=>time(),
			"music_id"=>$music_id,
			"dynamic_label_id"=>$dynamic_label_id,
			"classid"=>$classid,
			"author_center_id"=>$author_center_id,
			"anyway"=>$anyway,
			"coin"=>$coin,
		);
        if($is_ad==1){
            $isAuthAdvertiser=isAuthAdvertiser($uid);
            if(!$isAuthAdvertiser){
                $rs['code'] = 1009;
                $rs['msg'] = T('请先申请广告主体认证');
                return $rs;
            }
            $data['status']=1;
//            $data['ad_endtime']=strtotime($ad_endtime);
            $data['is_ad']=$is_ad;
            $data['ad_url']=$ad_url;
        }

		if($type>0){

			if($type==1 && $goodsid>0){ //商品

				
	            $domain2 = new Domain_Shop();
	            $where=[
	                'id'=>$goodsid
	            ];
	            $goodinfo = $domain2->getGoods($where);
	            if(!$goodinfo){
	                $rs['code'] = 1006;
	                $rs['msg'] = T('商品不存在');
	                return $rs;
	            }
	            if($goodinfo['uid']!=$uid){
	                $rs['code'] = 1002;
	                $rs['msg'] = T('非本人商品');
	                return $rs;
	            }
	            
	            if($goodinfo['status']==-2){
	                $rs['code'] = 1003;
	                $rs['msg'] = T('该商品已被下架');
	                return $rs;
	            }
	            
	            if($goodinfo['status']==-1){
	                $rs['code'] = 1004;
	                $rs['msg'] = T('该商品已下架');
	                return $rs;
	            }
	            
	            if($goodinfo['status']!=1){
	                $rs['code'] = 1005;
	                $rs['msg'] = T('该商品未通过审核');
	                return $rs;
	            }

	            $data['type']=$type;
	            $data['goodsid']=$goodsid;
		        


			}else if($type==2 && $goodsid>0){ //付费内容

				$domain3 = new Domain_Paidprogram();
				$where=[
					'id'=>$goodsid
				];
				$paidprogram_info=$domain3->getPaidProgram($where);

				if(!$paidprogram_info){
					$rs['code'] = 1007;
	                $rs['msg'] = T('付费内容不存在');
	                return $rs;
				}

				if($paidprogram_info['uid']!=$uid){
	                $rs['code'] = 1008;
	                $rs['msg'] = T('非本人发布的付费内容');
	                return $rs;
	            }

	            if($paidprogram_info['status']!=1){
	            	$rs['code'] = 1009;
	                $rs['msg'] = T('该付费内容未通过审核');
	                return $rs;
	            }

	            $data['type']=$type;
	            $data['goodsid']=$goodsid;

			}else if($type==3 && $goodsid>0){ //代售的平台商品

				$domain2 = new Domain_Shop();
	            $where=[
	                'id'=>$goodsid
	            ];
	            $goodinfo = $domain2->getGoods($where);
	            if(!$goodinfo){
	                $rs['code'] = 1006;
	                $rs['msg'] = T('商品不存在');
	                return $rs;
	            }

				//判断是否是代售商品
				$where=[];
				$where['uid']=$uid;
				$where['status']=1;

				$is_sale=checkUserSalePlatformGoods($where);
				if(!$is_sale){
					$rs['code'] = 1008;
	                $rs['msg'] = T('未代售该商品');
	                return $rs;
				}

				$data['type']=1;
	            $data['goodsid']=$goodsid;

			}
		}
        if($is_popular){
            $checkdata=array(
                'uid'=>$uid,
                'price'=>$price,
                'timestamp'=>$timestamp,
                'nonce'=>$nonce,
            );

//            $issign=checkSign($checkdata,$sign);
//            if(!$issign){
//                $rs['code']=1001;
//                $rs['msg']=T('签名错误');
//                $rs['sign'] = getSignUrl($checkdata);
//                return $rs;
//            }
//
//            $key = 'getNonce_'.$uid.'_'.$nonce;
//            $get_nonce = getcaches($key);
//            if ($get_nonce) {
//                $rs['code']=1001;
//                $rs['msg']=T('非法操作');
//                return $rs;
//            }else{
//                setcaches($key,1,300);
//            }
//
//            $now = time();
//            $timestamp = (int)$timestamp+300;
//            if($now>$timestamp){
//                $rs['code']=1001;
//                $rs['msg']=T('非法操作');
//                return $rs;
//            }
        }

		$domain = new Domain_Video();
		$info = $domain->setVideo($data,$music_id,$is_popular,$price,$duration);
		if($info==1007){
			$rs['code']=1007;
			$rs['msg']=T('视频分类不存在');
			return $rs;
		}else if($info==1020){
			$rs['code']=1020;
			$rs['msg']=T('非VIP用户每天可上传视频数有限');
			return $rs;
		}else if($info==1003){
			$rs['code']=1003;
			$rs['msg']=T('钻石不足，无法上热门');
			return $rs;
		}else if($info==1008){
			$rs['code']=1008;
			$rs['msg']=T('请先进行身份认证或等待审核');
			return $rs;
		}else if($info==1009){
			$rs['code']=1009;
			$rs['msg']=T('话题标签不存在');
			return $rs;
		}else if(!$info){
			$rs['code']=1001;
			$rs['msg']=T('发布失败');
			return $rs;
		}

		$rs['info'][0]['id']=$info['id'];
		$rs['info'][0]['thumb_s']=get_upload_path($thumb_s);
		$rs['info'][0]['title']=$title;
		return $rs;
	}


    /**
     * 礼物列表
     * @desc 用于获取礼物列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].coin 余额
     * @return array info[0].giftlist 礼物列表
     * @return string info[0].giftlist[].id 礼物ID
     * @return string info[0].giftlist[].type 礼物类型
     * @return string info[0].giftlist[].mark 礼物标识
     * @return string info[0].giftlist[].giftname 礼物名称
     * @return string info[0].giftlist[].needcoin 礼物价格
     * @return string info[0].giftlist[].gifticon 礼物图片
     * @return string msg 提示信息
     */
    public function getGiftList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Live();
        $giftlist=$domain->getGiftList();
        $proplist=$domain->getPropgiftList();

        $domain2 = new Domain_User();
        $coin=$domain2->getBalance($uid);

        $rs['info'][0]['giftlist']=$giftlist;
        $rs['info'][0]['proplist']=$proplist;
        $rs['info'][0]['coin']=$coin['coin'];
        return $rs;
    }

    /**
     * 赠送礼物
     * @desc 用于赠送礼物
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].gifttoken 礼物token
     * @return string info[0].level 用户等级
     * @return string info[0].coin 用户余额
     * @return string msg 提示信息
     */
    public function sendGift() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);
        $giftid=checkNull($this->giftid);
        $giftcount=checkNull($this->giftcount);
        $ispack=checkNull($this->ispack);
        $is_sticker=checkNull($this->is_sticker);
        $timestamp=checkNull($this->timestamp);
        $nonce=checkNull($this->nonce);
        $sign=checkNull($this->sign);

        $checkdata=array(
            'uid'=>$uid,
            'videoid'=>$videoid,
            'giftid'=>$giftid,
            'giftcount'=>$giftcount,
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

        $domain = new Domain_Live();
        if($is_sticker=='1'){
            $giftlist=$domain->getPropgiftList();

            $gift_info=array();
            foreach($giftlist as $k=>$v){
                if($giftid == $v['id']){
                    $gift_info=$v;
                }
            }
        }else{
            $giftlist=$domain->getGiftList();
            $gift_info=array();
            foreach($giftlist as $k=>$v){
                if($giftid == $v['id']){
                    $gift_info=$v;
                }
            }
        }

        if(!$gift_info){
            $rs['code']=1002;
            $rs['msg']=T('礼物信息不存在');
            return $rs;
        }


        $domain = new Domain_Video();
        $result=$domain->sendGift($uid,$videoid,$giftid,$giftcount,$ispack);

        if(isset($result['code'])&&$result['code']==400){
            return $result;
        }

        if($result==1001){
            $rs['code']=1001;
            $rs['msg']=T('余额不足');
            return $rs;
        }else if($result==1003){
            $rs['code']=1003;
            $rs['msg']=T('背包中数量不足');
            return $rs;
        }else if($result==1002){
            $rs['code']=1002;
            $rs['msg']=T('礼物信息不存在');
            return $rs;
        }else if($result==1004){
            $rs['code']=1004;
            $rs['msg']=T('视频不存在');
            return $rs;
        }

        $rs['info'][0]['gifttoken']=$result['gifttoken'];
        $rs['info'][0]['level']=$result['level'];
        $rs['info'][0]['coin']=$result['coin'];

        unset($result['gifttoken']);

        DI()->redis  -> set($rs['info'][0]['gifttoken'],json_encode($result));


        return $rs;
    }
    /**
     * 评论/回复
     * @desc 用于用户评论/回复 别人视频
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return int info[0].isattent 对方是否关注我
     * @return int info[0].u2t 我是否拉黑对方
     * @return int info[0].t2u 对方是否拉黑我
     * @return int info[0].comments 评论总数
     * @return int info[0].replys 回复总数
     * @return string msg 提示信息
     */
	public function setComment() {
        $rs = array('code' => 0, 'msg' => T('评论成功'), 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$touid=checkNull($this->touid);
		$videoid=checkNull($this->videoid);
		$commentid=checkNull($this->commentid);
		$parentid=checkNull($this->parentid);
		$content=checkNull($this->content);
		$at_info=$this->at_info;

		if(!$at_info){
			$at_info='';
		}
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		$sensitivewords=sensitiveField($content);
		if($sensitivewords==1001){
			$rs['code'] = 10011;
			$rs['msg'] = T('输入非法，请重新输入');
			return $rs;
        }
		
		if($commentid==0 && $commentid!=$parentid){
			$commentid=$parentid;
		}
		
		$data=array(
			'uid'=>$uid,
			'touid'=>$touid,
			'videoid'=>$videoid,
			'commentid'=>$commentid,
			'parentid'=>$parentid,
			'content'=>$content,
			'addtime'=>time(),
			'at_info'=>$at_info
		);

		/*var_dump($data);
		die;*/

        $domain = new Domain_Video();
        $result = $domain->setComment($data);

        if($result==1001){
            $rs['code']=1001;
            $rs['msg']= T("评论失败");
            return $rs;
        }

		$info=array(
			'isattent'=>'0',
			'u2t'=>'0',
			't2u'=>'0',
			'comments'=>$result['comments'],
			'replys'=>$result['replys'],
		);
		if($touid>0){
			$isattent=isAttention($touid,$uid);
			$u2t = isBlack($uid,$touid);
			$t2u = isBlack($touid,$uid);
			
			$info['isattent']=(string)$isattent;
			$info['u2t']=(string)$u2t;
			$info['t2u']=(string)$t2u;
		}
		
		$rs['info'][0]=$info;
		
		if($parentid!=0){
			 $rs['msg']=T('回复成功');			
		}
        return $rs;
    }	
	
   	/**
     * 阅读
     * @desc 用于视频阅读数累计
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function addView() {
        $rs = array('code' => 0, 'msg' => T('更新视频阅读次数成功'), 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$random_str=checkNull($this->random_str);

		//md5加密验证字符串
		$str=md5($uid.'-'.$videoid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = T('更新视频阅读次数失败');
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}


        $domain = new Domain_Video();
        $res = $domain->addView($uid,$videoid);

        return $rs;
    }

   	/**
     * 上热门
     * @desc 用于视频上热门
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function upPopular() {
        $rs = array('code' => 0, 'msg' => T('上热门成功'), 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$price=checkNull($this->price);
		$duration=checkNull($this->duration);
        $timestamp=checkNull($this->timestamp);
        $nonce=checkNull($this->nonce);
        $sign=checkNull($this->sign);

        $checkdata=array(
            'uid'=>$uid,
            'videoid'=>$videoid,
            'price'=>$price,
            'timestamp'=>$timestamp,
            'nonce'=>$nonce,
        );

        $issign=checkSign($checkdata,$sign);
//        $getsign=getSignUrl($checkdata);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']=T('签名错误');
            $rs['sign'] = getSignUrl($checkdata);
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

        $domain = new Domain_Video();
        $res = $domain->upPopular($uid,$videoid,$price,$duration);
        if($res == 1001){
            $rs['code'] = 1001;
            $rs['msg'] = T("视频已上热门，暂时不能付费");
            return $rs;
        }elseif($res == 1002){
            $rs['code'] = 1002;
            $rs['msg'] = T("视频不存在");
//            $rs['sign'] = $getsign;
            return $rs;
        }elseif($res == 1003){
            $rs['code'] = 1003;
            $rs['msg'] = T("钻石不足，无法上热门");
//            $rs['sign'] = $getsign;
            return $rs;
        }

        return $rs;
    }

   	/**
     * 获取上热门规则
     * @desc 用于获取上热门规则
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getPopularRule() {
        $rs = array('code' => 0, 'msg' => T(''), 'info' => array());

        $coin = [
            '100' => '100',
            '500' => '500',
            '1000' => '1000',
            '5000' => '5000',
            '10000' => '10000',
        ];
        $time = [
            '6' => '6小时',
            '12' => '12小时',
            '24' => '24小时',
        ];
        $rs['info'][0]['coin'] = $coin;
        $rs['info'][0]['time'] = $time;

        return $rs;
    }
   	/**
     * 点赞
     * @desc 用于视频点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addLike() {
        $rs = array('code' => 0, 'msg' => T('点赞成功'), 'info' => array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);
		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
        $domain = new Domain_Video();
        $result = $domain->addLike($uid,$videoid);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = T("视频已删除");
			return $rs;
		}else if($result==1002){
			$rs['code'] = 1002;
			$rs['msg'] = T("不能给自己点赞");
			return $rs;
		}
		$rs['info'][0]=$result;
        return $rs;
    }

   	/**
     * 点赞
     * @desc 用于视频推荐数累计
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
	public function addRecommend() {
        $rs = array('code' => 0, 'msg' => T('点赞成功'), 'info' => array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);
		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_Video();
        $result = $domain->addRecommend($uid,$videoid);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = T("视频已删除");
			return $rs;
		}else if($result==1002){
			$rs['code'] = 1002;
			$rs['msg'] = T("不能给自己推荐");
			return $rs;
		}
		$rs['info'][0]=$result;
        return $rs;
    }

    /**
     * 不感兴趣
     * @desc 用于 操作不感兴趣
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function setUnconcern() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Video();
        $info = $domain->SetUnconcern($uid,$videoid);

        $rs['msg'] = T('不感兴趣成功');
        $rs['info']=$info;
        return $rs;
    }

   	/**
     * 踩一下
     * @desc 用于视频踩数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isstep 是否踩
     * @return string info[0].steps 踩数量
     * @return string msg 提示信息
     */
	public function addStep() {
        $rs = array('code' => 0, 'msg' => T('踩一踩成功'), 'info' => array());
        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);

		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'][0] = $domain->addStep($uid,$videoid);

        return $rs;
    }

   	/**
     * 视频分享
     * @desc 用于视频分享数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isshare 是否分享
     * @return string info[0].shares 分享数量
     * @return string msg 提示信息
     */
	public function addShare() {
        $rs = array('code' => 0, 'msg' => T('分享成功'), 'info' => array());

        $uid=checkNull($this->uid);
		$videoid=checkNull($this->videoid);
		$random_str=checkNull($this->random_str);

		//md5加密验证字符串
		$str=md5($uid.'-'.$videoid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!=$str){
			$rs['code'] = 1001;
			$rs['msg'] = T('视频分享数修改失败');
			return $rs;
		}
		
        $domain = new Domain_Video();
        $rs['info'][0] = $domain->addShare($uid,$videoid);

        return $rs;
    }	

   	/**
     * 拉黑视频
     * @desc 用于拉黑视频
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isblack 是否拉黑
     * @return string msg 提示信息
     */
	public function setBlack() {
        $rs = array('code' => 0, 'msg' => T('操作成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);

		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'][0] = $domain->setBlack($uid,$videoid);

        return $rs;
    }	
	
   	/**
     * 评论/回复 点赞
     * @desc 用于评论/回复 点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addCommentLike() {
        $rs = array('code' => 0, 'msg' => T('点赞成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $commentid=checkNull($this->commentid);

        $isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_Video();
         $res= $domain->addCommentLike($uid,$commentid);
         if($res==1001){
         	$rs['code']=1001;
         	$rs['msg']=T('评论信息不存在');
         	return $rs;
         }else if($res==1002){
         	$rs['code']=1002;
             $rs['msg'] = T("不能给自己点赞");
         	return $rs;
         }
         $rs['info'][0]=$res;

        return $rs;
    }	
	/**
     * 获取热门视频
     * @desc 用于获取热门视频
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
        $p=checkNull($this->p);
		$isBan=isBan($this->uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

		$key='videoHot_'.$p;

		$info=getcaches($key);

		if(!$info){
			$domain = new Domain_Video();
			$info= $domain->getVideoList($uid,$p);

			if($info==10010){
				$rs['code'] = 0;
				$rs['msg'] = T("暂无视频列表");
				return $rs;
			}
			
			setcaches($key,$info,60);
		}

        
		$rs['info'] =$info;
        return $rs;
    }

	/**
     * 获取关注视频
     * @desc 用于获取关注视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
	 * @return string info[].islike 是否点赞 
	 * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getAttentionVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$p=checkNull($this->p);
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

//		$key='attentionVideoLists_'.$uid.'_'.$p;
//        $info=getcaches($key);
//
//        if(!$info){
        	$domain = new Domain_Video();
        	$info=$domain->getAttentionVideo($uid,$p);
        	if($info==0){
        		$rs['code']=0;
                $rs['msg']=T("暂无视频列表");
                return $rs;
        	}

//            setcaches($key,$info,60);
//        }
        
        $rs['info'] = $info;

        return $rs;
    }

	/**
     * 获取我的广告视频
     * @desc 用于获取我的广告视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return string msg 提示信息
     */
	public function getAdVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$p=checkNull($this->p);
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_Video();
        $info=$domain->getAdVideo($uid,$p);
        if($info==0){
            $rs['code']=0;
            $rs['msg']=T("暂无视频列表");
            return $rs;
        }

        $rs['info'] = $info;

        return $rs;
    }
	/**
     * 视频详情
     * @desc 用于获取视频详情
     * @return int code 操作码，0表示成功，1000表示视频不存在
     * @return array info[0] 视频详情
     * @return object info[0].userinfo 用户信息
     * @return string info[0].datetime 格式后的时间差
     * @return string info[0].isattent 是否关注
     * @return string info[0].likes 点赞数
     * @return string info[0].comments 评论数
     * @return string info[0].views 阅读数
     * @return string info[0].steps 踩一踩数量
     * @return string info[0].shares 分享数量
     * @return string info[0].islike 是否点赞
     * @return string info[0].isstep 是否踩
     * @return string info[0].related_topics 活动话题标签（创作者中心投稿显示）
     * @return string info[0].coin 需要付费钻石
     * @return string info[0].anyway 横竖屏(封面-高/宽)，大于1表示竖屏,小于1表示横屏
     * @return string msg 提示信息
     */
	public function getVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);

        $domain = new Domain_Video();
        $result = $domain->getVideo($uid,$videoid);
		if($result==1000){
			$rs['code'] = 1000;
			$rs['msg'] = T("视频已删除");
			return $rs;
		}
		if(!empty($result['code'])&&$result['code']==1001){
			return $result;
		}
		$rs['info'][0]=$result;

        return $rs;
    }

    /**
     * 确认观看付费视频
     * @desc 用于确认观看付费视频
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function setVideoCoin() {
        $rs = array('code' => 0, 'msg' => T('操作成功'), 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $isBan=isBan($uid);
        if($isBan=='0'){
            $rs['code'] = 700;
            $rs['msg'] = T('该账号已被禁用');
            return $rs;
        }

        $domain = new Domain_Video();
        $result = $domain->setVideoCoin($uid,$videoid);
        if($result==1000){
            $rs['code'] = 1000;
            $rs['msg'] = T("视频已删除");
            return $rs;
        }
        if(!empty($result['code'])&&$result['code']==1001){
            return $result;
        }
        $rs['info'][0]=$result;

        return $rs;
    }
    /**
     * 视频评论列表
     * @desc 用于获取视频评论列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].comments 评论总数
     * @return array info[0].commentlist 评论列表
     * @return object info[0].commentlist[].userinfo 用户信息
	 * @return string info[0].commentlist[].datetime 格式后的时间差
	 * @return string info[0].commentlist[].replys 回复总数
	 * @return string info[0].commentlist[].likes 点赞数
	 * @return string info[0].commentlist[].islike 是否点赞
	 * @return array info[0].commentlist[].replylist 回复列表
     * @return string msg 提示信息
     */
	public function getComments() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);
        $p=checkNull($this->p);

		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'][0] = $domain->getComments($uid,$videoid,$p);

        return $rs;
    }	
	
	/**
     * 回复列表
     * @desc 用于获取视频评论列表
     * @return int code 操作码，0表示成功
     * @return array info 评论列表
     * @return object info[].userinfo 用户信息
	 * @return string info[].datetime 格式后的时间差
	 * @return object info[].tocommentinfo 回复的评论的信息
	 * @return object info[].tocommentinfo.content 评论内容
	 * @return string info[].likes 点赞数
	 * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getReplys() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $commentid=checkNull($this->commentid);
        $p=checkNull($this->p);

		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'] = $domain->getReplys($uid,$commentid,$p);

        return $rs;
    }	
	
	
	/**
     * 我的视频(弃用)
     * @desc 用于获取我发布的视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getMyVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$p=checkNull($this->p);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'] = $domain->getMyVideo($uid,$p);

        return $rs;
    }	
	
	/**
     * 删除视频
     * @desc 用于删除视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function del() {
        $rs = array('code' => 0, 'msg' => T('删除成功'), 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
        //DI()->logger->debug('del_video: ' . $uid.'--'.$videoid);
        file_put_contents('./del_video.txt',date('Y-m-d H:i:s').$uid.'--'.$videoid."\r\n",FILE_APPEND);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
        $domain = new Domain_Video();
        $info = $domain->del($uid,$videoid);
        file_put_contents('./del_video.txt',date('Y-m-d H:i:s').$uid.'--'.$info."\r\n",FILE_APPEND);
        return $rs;
    }	

	/**
     * 举报视频
     * @desc 用于删除视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function report() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$content=checkNull($this->content);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
		$data=array(
			'uid'=>$uid,
			'videoid'=>$videoid,
			'content'=>$content,
			'addtime'=>time(),
		);
        $domain = new Domain_Video();
        $info = $domain->report($data);
		
		if($info==1000){
			$rs['code'] = 1000;
			$rs['msg'] = T('视频不存在');
			return $rs;
		}

        return $rs;
    }	


	/**
     * 个人主页视频
     * @desc 用于获取个人主页视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getHomeVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}
		
		

        $domain = new Domain_Video();
        $info = $domain->getHomeVideo($uid,$touid,$p);
		
		
		$rs['info']=$info;

        return $rs;
    }

	/**
     * 个人主页喜欢的视频
     * @desc 用于获取个人主页视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getHomeLikeVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = T('该账号已被禁用');
			return $rs;
		}



        $domain = new Domain_Video();
        $info = $domain->getHomeLikeVideo($uid,$touid,$p);


		$rs['info']=$info;

        return $rs;
    }

	/**
     * 个人主页收藏的视频
     * @desc 用于获取个人主页收藏的视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getHomeCollectVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
		$p=checkNull($this->p);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Video();
        $info = $domain->getHomeCollectVideo($uid,$p);

		$rs['info']=$info;

        return $rs;
    }

	/* 检测文件后缀 */
	protected function checkExt($filename){
		$config=array("jpg","png","jpeg");
		$ext   =   pathinfo(strip_tags($filename), PATHINFO_EXTENSION);

		return empty($config) ? true : in_array(strtolower($ext), $config);
	}


    /**
     * 获取推荐视频
     * @desc 用户获取推荐视频
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return string info[0].id 视频id
     * @return string info[0].uid 视频发布者id
     * @return string info[0].title 视频标题
     * @return string info[0].thumbs 视频封面
     * @return string info[0].thumbs 视频小封面
     * @return string info[0].href 视频链接
     * @return string info[0].likes 视频被喜欢总数
     * @return string info[0].views 视频被观看总数
     * @return string info[0].comments 视频评论总数
     * @return string info[0].steps 视频被踩总数
     * @return string info[0].shares 视频分享总数
     * @return string info[0].addtime 视频发布时间
     * @return string info[0].lat 纬度
     * @return string info[0].lng 经度
     * @return string info[0].city 城市
     * @return string info[0].isdel 是否删除
     * @return string info[0].datetime 视频发布时间格式化
     * @return string info[0].islike 是否喜欢了该视频
     * @return string info[0].isattent 是否关注
     * @return string info[0].isstep 是否踩了该视频
     * @return string info[0].isdialect 是否方言秀
     * @return object info[0].userinfo 视频发布者信息
     * @return string info[0].userinfo.id 视频发布者id
     * @return string info[0].userinfo.user_nicename 视频发布者昵称
     * @return string info[0].userinfo.avatar 视频发布者头像
     * @return string info[0].userinfo.coin 视频发布者钻石
     * @return string info[0].userinfo.avatar_thumb 视频发布者小头像
     * @return string info[0].userinfo.sex 视频发布者性别
     * @return string info[0].userinfo.signature 视频发布者签名
     * @return string info[0].userinfo.privince 视频发布者省份
     * @return string info[0].userinfo.city 视频发布者市
     * @return string info[0].userinfo.birthday 视频发布者生日
     * @return string info[0].userinfo.age 视频发布者年龄
     * @return string info[0].userinfo.praise 视频发布者被赞总数
     * @return string info[0].userinfo.fans 视频发布者粉丝数
     * @return string info[0].userinfo.follows 视频发布者关注数
     * @return object info[0].musicinfo 背景音乐信息
     * @return string info[0].musicinfo.id 背景音乐id
     * @return string info[0].musicinfo.title 背景音乐标题
     * @return string info[0].musicinfo.author 背景音乐作者
     * @return string info[0].musicinfo.img_url 背景音乐封面地址
     * @return string info[0].musicinfo.length 背景音乐长度
     * @return string info[0].musicinfo.file_url 背景音乐地址
     * @return string info[0].musicinfo.use_nums 背景音乐使用次数
     */
    public function getRecommendVideos(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

    	$uid=checkNull($this->uid);
    	
    	if($uid>0){ //非游客

    		$isBan=isBan($uid);
			if($isBan=='0'){
				$rs['code'] = 700;
				$rs['msg'] = T('该账号已被禁用');
				return $rs;
			}
    	}
		

		$p=checkNull($this->p);
		$isstart=checkNull($this->isstart);


		$key='videoRecommend_'.$p;

		$info=getcaches($key);

//		if(!$info){

			$domain=new Domain_Video();
			$info=$domain->getRecommendVideos($uid,$p,$isstart);

			if($info==1001){
				$rs['code']=1001;
				$rs['msg']=T("暂无视频列表");
				return $rs;
			}
//
//			setcaches($key,$info,30);
//
//		}

		



		$rs['info']=$info;

		return $rs;
    }

	/**
	 * 获取附近的视频列表
	 * @desc 用于获取附近的视频列表
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function getNearby(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$lng=checkNull($this->lng);
		$lat=checkNull($this->lat);
		$p=checkNull($this->p);

		if($lng==''){
			return $rs;
		}
		
		if($lat==''){
			return $rs;
		}
		
		if(!$p){
			$p=1;
		}

		$key='videoNearby_'.$lng.'_'.$lat.'_'.$p;

		$info=getcaches($key);

		if(!$info){
			$domain = new Domain_Video();
			$info = $domain->getNearby($uid,$lng,$lat,$p);

			if($info==1001){
				return $rs;
			}
			
			setcaches($key,$info,120);
		}

		$rs['info'] = $info;
        return $rs;
	}

	/**
     * 获取视频举报分类列表
     * @desc 获取视频举报分类列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
	public function getReportContentlist() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Video();
        $res = $domain->getReportContentlist();

        if($res==1001){
        	$rs['code']=1001;
        	$rs['msg']=T('暂无举报分类列表');
        	return $rs;
        }
        $rs['info']=$res;
        return $rs;
    }

    /**
     * 更新视频看完次数
     * @desc 更新视频看完次数
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setConversion(){

    	$rs = array('code' => 0, 'msg' => T('视频完整观看次数更新成功'), 'info' => array());
    	$uid=checkNull($this->uid);
    	$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$random_str=checkNull($this->random_str);

		//md5加密验证字符串
		$str=md5($uid.'-'.$videoid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = T('视频完整观看次数更新失败');
			return $rs;
		}

		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		

		$domain = new Domain_Video();
        $res = $domain->setConversion($videoid);
        

        return $rs;

    }

	 /**
     * 获取分类下的视频
     * @desc 获取分类下的视频
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息 
     * @return array info
     **/
    
    public function getClassVideo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $videoclassid=checkNull($this->videoclassid);
        $uid=checkNull($this->uid);
        $p=checkNull($this->p);
        
        if(!$videoclassid){
            return $rs;
        }
        $domain=new Domain_Video();
        $res=$domain->getClassVideo($videoclassid,$uid,$p);

        $rs['info']=$res;
        return $rs;
    }

	 /**
     * 获取音乐下的视频
     * @desc 获取音乐下的视频
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息
     * @return array info
     **/

    public function getMusicVideo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $music_id=checkNull($this->music_id);
        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        if(!$music_id){
            return $rs;
        }
        $domain=new Domain_Video();
        $res=$domain->getMusicVideo($music_id,$uid,$p);

        $rs['info']=$res;
        return $rs;
    }

	 /**
     * 获取话题标签下的视频
     * @desc 获取话题标签下的视频
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息
     * @return array info
     **/

    public function getDynamicLabelVideo(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $dynamic_label_id=checkNull($this->dynamic_label_id);
        $uid=checkNull($this->uid);
        $p=checkNull($this->p);

        if(!$dynamic_label_id){
            return $rs;
        }
        $domain=new Domain_Video();
        $res=$domain->getDynamicLabelVideo($dynamic_label_id,$uid,$p);

        $rs['info']=$res;
        return $rs;
    }
	
	
	
	/**
	 * 用户开始观看视频
	 * @desc 用于每日任务统计用户观看时长
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function startWatchVideo(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
        $token=checkNull($this->token);


        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		/*观看视频计时---每日任务*/
		$key='watch_video_daily_tasks_'.$uid;
		$time=time();
		setcaches($key,$time);

		return $rs;	
	}
	
	
	/**
	 * 用户结束观看视频
	 * @desc 用于每日任务统计用户观看时长
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function endWatchVideo(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);


        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

	
		/*观看视频计时---每日任务--取出用户起始时间*/
		$key='watch_video_daily_tasks_'.$uid;
		$starttime=getcaches($key);
		if($starttime){
            $endtime=time();  //当前时间

            //处理用户每观看60秒视频奖励钻石逻辑
            $time = $endtime - $starttime;
            $domain = new Domain_Video();
            $domain->dealUserVideoRewards($uid,$time);

			$data=[
				'type'=>'6',
				'starttime'=>$starttime,
				'endtime'=>$endtime,
			];
			dailyTasks($uid,$data);
			//删除当前存入的时间
			delcache($key);

		}

		return $rs;	
	}
	
	/**
     * 删除评论
     * @desc 用于删除评论以及子级评论
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function delComments() {
        $rs = array('code' => 0, 'msg' => T('删除成功'), 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$commentid=checkNull($this->commentid);
		$commentuid=checkNull($this->commentuid);


		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		
        $domain = new Domain_Video();
        $info = $domain->delComments($uid,$videoid,$commentid,$commentuid);
		
		if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = T('视频信息错误,请稍后操作~');
		}else if($info==1002){
			$rs['code'] = 1002;
			$rs['msg'] = T('您无权进行删除操作~');
		}

        return $rs;
    }
}
