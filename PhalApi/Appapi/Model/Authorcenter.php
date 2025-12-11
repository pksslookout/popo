<?php

class Model_Authorcenter extends PhalApi_Model_NotORM {
	/* 引导页 */
	public function getAuthorCenterList($p) {
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;
        $time = time();
        $where=" active_end_time >={$time} and active_start_time<={$time} and is_status=1";
        $authorCenterList=DI()->notorm->author_center
            ->select('id,title,title_en,active_end_time,active_start_time,thumb,classifyid')
            ->where($where)
            ->order("id DESC")
            ->limit($start,$pnum)
            ->fetchAll();
        $lang = GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('author_center', 'title', $lang);
        }
        foreach($authorCenterList as $k=>$v){
            if($lang=='en'){
                $authorCenterList[$k]['title']=$v['title_'.$lang];
            }else{
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $authorCenterList[$k]['title']=$translate[$v['id']];
                    }
                }
            }
            $authorCenterList[$k]['thumb']=get_upload_path($v['thumb']);
            $authorCenterList[$k]['classify']=$this->getClassify()[$v['classifyid']];
        }
        return $authorCenterList;
	}

	public function getCollectAuthorCenterList($p, $uid) {
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;
        $collection=DI()->notorm->author_center_collection
            ->select("author_center_id")
            ->where("uid=$uid and status=1")
            ->fetchAll();
        $authorCenterList = [];
        if($collection){
            $author_center_ids=array_column($collection,'author_center_id');
            $author_center_ids=implode(",",$author_center_ids);

            $where="id in ({$author_center_ids}) and is_status=1";
            $authorCenterList = DI()->notorm->author_center
                ->select('id,active_end_time,active_start_time,thumb,classifyid')
                ->where($where)
                ->order("id DESC")
                ->limit($start, $pnum)
                ->fetchAll();

            foreach ($authorCenterList as $k => $v) {
                $authorCenterList[$k]['thumb'] = get_upload_path($v['thumb']);
                $authorCenterList[$k]['classify'] = $this->getClassify()[$v['classifyid']];
            }
        }
        return $authorCenterList;
	}

	public function getAuthorCenterInfo($author_center_id,$uid) {

        $data=DI()->notorm->author_center
            ->where('id=?',$author_center_id)
            ->fetchOne();

        if(!$data){
            return $data;
        }
        $lang=GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('author_center', 'title', $lang);
        }
        if($lang=='en'){
            $data['title'] = $data['title_'.$lang];
        }else{
            if($lang!='zh_cn'){
                if(isset($translate[$v['id']])){
                    $data['title']=$translate[$author_center_id];
                }
            }
        }
        $data['classify'] = $this->getClassify()[$data['classifyid']];
        $data['thumb'] = get_upload_path($data['thumb']);
        $data['hot'] = $this->getHot($data['id']);

        $isexist =DI()->notorm->author_center_collection
            ->select("author_center_id")
            ->where("author_center_id=$author_center_id and uid=$uid and status=1")
            ->fetchOne();
        if($isexist){
            $data['iscollect'] = 1;
        }else{
            $data['iscollect'] = 0;
        }
        return $data;
	}

	public function getBusinessData($uid) {
        $key='getBusinessData';
        $data=getcaches($key);
        if(!$data) {
            $time = time() - (7 * 24 * 60 * 60);
            $data = [];
            $data['contribute_count'] = (int)DI()->notorm->video->where("uid=$uid and status=1 and author_center_id <> 0 and addtime > $time")->count();
            $data['views'] = (int)DI()->notorm->video->where("uid=$uid and status=1 and author_center_id <> 0 and addtime > $time")->sum('views');
            $data['likes'] = (int)DI()->notorm->video->where("uid=$uid and status=1 and author_center_id <> 0 and addtime > $time")->sum('likes');
            $data_views_count = (int)DI()->notorm->video->where("uid=$uid and status=1 and author_center_id <> 0 and addtime > $time and views > 0")->count();
            if ($data['contribute_count'] > 0 && $data_views_count > 0) {
                $percent = $data_views_count / $data['contribute_count'];
                $data['percent'] = number_format($percent, 2) * 100;
                $data['percent'] = $data['percent'] . '%';
            } else {
                $data['percent'] = '0%';
            }
            $data['fans_count'] = (int)DI()->notorm->user_attention->where("touid=$uid and status=1")->count();
            $data['add_fans_count'] = (int)DI()->notorm->user_attention->where("touid=$uid and status=1 and addtime > $time")->count();
            $data['cancel_fans_count'] = (int)DI()->notorm->user_attention->where("touid=$uid and status=0 and addtime > $time")->count();
            $sql = 'SELECT COUNT(*) AS SEX_COUNT '
                . 'FROM cmf_user_attention AS cua INNER JOIN cmf_user AS u '
                . 'ON cua.uid = u.id '
                . 'WHERE cua.touid = ' . $uid . ' and cua.status=1 and u.sex = 1 ';
            $data_fans_count_1 = $this->getORM()->queryAll($sql)[0]['SEX_COUNT'];
            if ($data['fans_count'] > 0 && $data_fans_count_1 > 0) {
                $percent = $data_fans_count_1 / $data['fans_count'];
                $data['sex_percent'] = number_format($percent, 2) * 100;
                $data['sex_percent'] = $data['sex_percent'] . '%';
            } else {
                $data['sex_percent'] = '0%';
            }

            // 统计七日新增投稿数量曲线图
            $where = "uid=$uid and status=1 and author_center_id <> 0 and addtime > $time";
            $data_contribute = DI()->notorm->video->select("FROM_UNIXTIME(addtime,'%m-%d') AS date")->where($where)->fetchAll();
            $data_contribute_array = [];
            foreach ($data_contribute as $v) {
                $data_contribute_array[] = $v['date'];
            }
            $data_contribute_counts = array_count_values($data_contribute_array);
            $data['contribute_week_name'] = get_day_eight('', 'm-d');
            foreach ($data['contribute_week_name'] as $k => $v) {
                $data['contribute_week_data'][$k] = (int)$data_contribute_counts[$v];
            }

            // 统计七日新增粉丝数量曲线图
            $where = "touid=$uid and status=1 and addtime > $time";
            $data_fans = DI()->notorm->user_attention->select("FROM_UNIXTIME(addtime,'%m-%d') AS date")->where($where)->fetchAll();
            $data_fans_array = [];
            foreach ($data_fans as $v) {
                $data_fans_array[] = $v['date'];
            }
            $data_fans_counts = array_count_values($data_fans_array);
            $data['fans_week_name'] = get_day_eight('', 'm-d');
            foreach ($data['fans_week_name'] as $k => $v) {
                $data['fans_week_data'][$k] = (int)$data_fans_counts[$v];
            }
            setcaches($key,$data,180);
        }

        return $data;
	}

	public function getVideoList($author_center_id, $uid, $p,$type) {
        if($p<1){
            $p=1;
        }
        $nums=20;
        $start=($p-1)*$nums;

        $where="isdel=0 and status=1 and is_ad=0 and author_center_id=$author_center_id";
        if($type == 1){
            $where.=" and uid = $uid";
        }else{
            $where.=" and uid <> $uid";
        }

        $video=DI()->notorm->video
            ->select("*")
            ->where($where)
            ->order("RAND()")
            ->limit($start,$nums)
            ->fetchAll();

        foreach($video as $k=>$v){

            $v=handleVideo($uid,$v);

            $video[$k]=$v;

        }


        return $video;
	}

	public function getContributeVideoList($uid, $p, $sort,$day) {
        if($p<1){
            $p=1;
        }
        $nums=20;
        $start=($p-1)*$nums;

        $where="isdel=0 and status=1 and is_ad=0 and author_center_id<>0 and uid = $uid";
        if(empty($sort)){
            $sort = 'addtime DESC';
        }
        if(!empty($day)){
            $time = time() - ($day*24*60*60);
            $where.=" and addtime > $time";
        }

        $video=DI()->notorm->video
            ->select("*")
            ->where($where)
            ->order($sort)
            ->limit($start,$nums)
            ->fetchAll();

        foreach($video as $k=>$v){

            $v=handleVideo($uid,$v);

            $video[$k]=$v;

        }


        return $video;
	}

    protected function getClassify(){
        $map[]=['is_status','=',1];
        $list=DI()->notorm->author_center_classify
            ->select('name','name_en','id')
            ->where($map)
            ->order("list_order asc")
            ->fetchAll();
        $lang = GL();
        if(!in_array($lang,['zh_cn','en'])) {
            $translate = get_language_translate('author_center_classify', 'name', $lang);
        }
        foreach($list as $v){
            if($lang=='en'){
                $list[$v['id']]=$v['name_en'];
            }else{
                $list[$v['id']]=$v['name'];
                if($lang!='zh_cn'){
                    if(isset($translate[$v['id']])){
                        $list[$v['id']]=$translate[$v['id']];
                    }
                }
            }
        }
        return $list;
    }

    protected function getHot($author_center_id){
        $where="author_center_id = $author_center_id and status=1 and isdel = 0";
        $count=DI()->notorm->video
            ->where($where)
            ->count();
        return $count;
    }


    /*收藏/取消收藏*/
    public function collectAuthorCenter($uid,$author_center_id){

        //判断活动是否存在
        $info=DI()->notorm->author_center->select("title,addtime")->where("id=?",$author_center_id)->fetchOne();


        if(!$info){
            return 1001;
        }

        //判断用户是否收藏过该视频
        $isexist=DI()->notorm->author_center_collection->select("*")->where("uid='{$uid}' and author_center_id='{$author_center_id}'")->fetchOne();


        //已经收藏过
        if($isexist){

            if($isexist['status']==1){ //已收藏
                //将状态改为取消收藏
                $result=DI()->notorm->author_center_collection->where("uid=? and author_center_id=?",$uid,$author_center_id)->update(array("status"=>0,"updatetime"=>time()));
                if($result!==false){
                    return 200;
                }else{
                    return 201;
                }
            }else{ //改为收藏

                //将状态改为收藏
                $result=DI()->notorm->author_center_collection->where("uid=? and author_center_id=?",$uid,$author_center_id)->update(array("status"=>1,"updatetime"=>time()));
                if($result!==false){
                    return 300;
                }else{
                    return 301;
                }
            }

        }else{

            //向收藏表中写入记录
            $data=array("uid"=>$uid,"author_center_id"=>$author_center_id,'addtime'=>time(),'status'=>1);
            $result=DI()->notorm->author_center_collection->insert($data);
            if($result!==false){
                return 300;
            }else{
                return 301;
            }
        }

    }
}
