<?php

class Model_Newmessage extends PhalApi_Model_NotORM {

	/* 获取系统所有最新消息数 */
	public function getAllNewMessage($uid) {

        DI()->redis->select(1);
        $key = 'new_message_all_count_'.$uid;
        $info = DI()->redis->get($key);

        if(!$info){

            $fans_count = (int)DI()->notorm->user_attention->where("touid = '{$uid}' and status = 1 and is_read = 0")->count();
            $like_video_count = (int)DI()->notorm->video_like->where("touid = '{$uid}' and is_read = 0")->count();
            $like_video_comments_count = (int)DI()->notorm->video_comments_like->where("touid = '{$uid}' and is_read = 0")->count();
            $like_count = $like_video_count + $like_video_comments_count;
            $at_count = (int)DI()->notorm->video_comments_at->where("touid = '{$uid}' and is_read = 0")->count();
            $comment_count = (int)DI()->notorm->video_comments->where("touid = '{$uid}' and is_read = 0")->count();
            $system_count = (int)DI()->notorm->pushrecord->where("touid = '{$uid}' and is_read = 0")->count();
            $info['fans_count'] = $fans_count;
            $info['like_count'] = $like_count;
            $info['at_count'] = $at_count;
            $info['comment_count'] = $comment_count;
            $info['system_count'] = $system_count;
            $info['all_count'] = $info['fans_count'] + $info['like_count'] + $info['at_count'] + $info['comment_count'] + $info['system_count'];

            DI()->redis->set($key, json_encode($info), 30);

        }else{
            $info = json_decode($info, true);
        }

		return $info;

	}

	public function clearFansCount($uid) {

        DI()->notorm->user_attention->where("touid = '{$uid}' and status = 1 and is_read = 0")->update(array('is_read'=>1));
		return 0;

	}

	public function clearLikeCount($uid) {

        DI()->notorm->video_like->where("touid = '{$uid}' and is_read = 0")->update(array('is_read'=>1));
        DI()->notorm->video_comments_like->where("touid = '{$uid}' and is_read = 0")->update(array('is_read'=>1));
        DI()->redis->select(1);
        $key = 'new_message_all_count_'.$uid;
        DI()->redis->del($key);
		return 0;

	}

	public function clearAtCount($uid) {

        DI()->notorm->video_comments_at->where("touid = '{$uid}' and is_read = 0")->update(array('is_read'=>1));
        DI()->redis->select(1);
        $key = 'new_message_all_count_'.$uid;
        DI()->redis->del($key);
		return 0;

	}

	public function clearCommentCount($uid) {

        DI()->notorm->video_comments->where("touid = '{$uid}' and is_read = 0")->update(array('is_read'=>1));
        DI()->redis->select(1);
        $key = 'new_message_all_count_'.$uid;
        DI()->redis->del($key);
		return 0;

	}

	public function clearSystemCount($uid) {

        DI()->notorm->pushrecord->where("touid = '{$uid}' and is_read = 0")->update(array('is_read'=>1));
        DI()->redis->select(1);
        $key = 'new_message_all_count_'.$uid;
        DI()->redis->del($key);
		return 0;

	}

    public function clearAllCount($uid) {

        DI()->redis->select(1);
        try {
            DI()->notorm->beginTransaction('db_appapi');
            DI()->notorm->user_attention->where("touid = '{$uid}' and status = 1 and is_read = 0")->update(array('is_read' => 1));
            DI()->notorm->video_like->where("touid = '{$uid}' and is_read = 0")->update(array('is_read' => 1));
            DI()->notorm->video_comments_like->where("touid = '{$uid}' and is_read = 0")->update(array('is_read' => 1));
            DI()->notorm->video_comments_at->where("touid = '{$uid}' and is_read = 0")->update(array('is_read' => 1));
            DI()->notorm->video_comments->where("touid = '{$uid}' and is_read = 0")->update(array('is_read' => 1));
            DI()->notorm->pushrecord->where("touid = '{$uid}' and is_read = 0")->update(array('is_read' => 1));
            DI()->notorm->commit('db_appapi');
        }catch(\Exception $e){
            DI()->notorm->rollback('db_appapi');
            return ['code'=>400,'msg'=>$e->getMessage()];
        }
        DI()->redis->select(1);
        $key = 'new_message_all_count_'.$uid;
        DI()->redis->del($key);
        return 0;

    }

}
