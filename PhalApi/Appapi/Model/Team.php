<?php

class Model_Team extends PhalApi_Model_NotORM {


    /*获取我的团队页面我的基本信息*/
    public function getMyInfo($uid){
        $info=DI()->notorm->user
            ->select("id,user_nicename,avatar,islive,bg_img,avatar_thumb,sex,consumption,votestotal,team_level,team_count,agent_count,team_live_count,team_vip_count")
            ->where('id=?',$uid)
            ->fetchOne();
        if($info){
            $info['bg_img']=get_upload_path($info['bg_img']);
            $info['avatar']=get_upload_path($info['avatar']);
            $info['avatar_thumb']=get_upload_path($info['avatar_thumb']);
            $info['level']=getLevel($info['consumption']);
            $level = 1;
            for ($i = 1; $i <= 10; $i++) {
                if ($info['level'] <= 10 * $i) {
                    $level = $i;
                    break;
                }
            }
            if ($info['consumption'] < 1000) {
                $info['level'] = '0';
            }
            $info['level_thumb'] = get_upload_path('images/new_level_v2/level_' . $level . '@2x.png');
            $info['level_bg_thumb'] = get_upload_path('images/new_level_v2/level_bg_' . $level . '@2x.png');
            if ($info['islive'] == 1 && $info['votestotal'] > 0) {
                $info['level_anchor'] = getLevelAnchor($info['votestotal']);
                $info['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_' . $info['level_anchor'] . '@3x.png');
            } else {
                $info['level_anchor'] = '0';
                $info['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_1@3x.png');
            }
            $info['level_team']=$info['team_level'];
            if ($info['level_team'] > 0) {
                $info['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_' . $info['level_team'] . '@3x.png');
            } else {
                $info['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_1@3x.png');
            }
            $info['vip']=getUserVip($uid);
            $info['vip_thumb'] = get_upload_path('images/new_level_v2/VIP1@2x.png');
            $info['liang']=getUserLiang($uid);

            $info['level_family'] = '0';
            $family_user_count = '0';
            // 公会信息获取
            $family = DI()->notorm->family->select('id,votes')->where('uid = ?', $uid)->fetchOne();
            if (!$family) {
                $family = DI()->notorm->family_user->select('familyid')->where('uid = ?', $uid)->fetchOne();
                if ($family) {
                    $family = DI()->notorm->family->select('id,votes')->where('id = ?', $family['familyid'])->fetchOne();
                    $family_user_count = DI()->notorm->family_user->select('familyid')->where('familyid = ?', $family['familyid'])->count();
                }
            }
            if ($family && $family_user_count > 10) {
                $level_family = getLevelFamily($family['votestotal']);
                $info['level_family'] = $level_family;
            }
            if ($info['level_family'] != 0) {
                $info['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_' . $info['level_family'] . '@3x.png');
            } else {
                $info['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_1@3x.png');
            }

            // 获取我的邀请人
            $agentinfo=DI()->notorm->agent->select("*")->where('uid=?',$uid)->fetchOne();
            if($agentinfo){
                $user_nicename = getUserInfo($agentinfo['one_uid'],1)['user_nicename'];
            }else{
                $user_nicename = '未设置';
            }
            $info['agent_nicename']=$user_nicename;

            $sql = 'SELECT COUNT(*) AS count '
                . 'FROM cmf_agent AS a INNER JOIN cmf_user AS u '
                . 'ON a.uid = u.id '
                . 'WHERE a.one_uid = '.$uid.' and u.isauth = 1 ';
            $list = $this->getORM()->queryAll($sql);
            $info['agent_auth_count']=$list['0']['count'];
            $info['agent_no_auth_count']=$info['agent_count'] - $list['0']['count'];

        }

        return $info;
    }

    /*获取直推列表*/
    public function getMyTeamLists($uid,$p,$isauth,$sort,$key){
        if($p<1){
            $p=1;
        }
        $pnum=10;
        $start=($p-1)*$pnum;
        $where = '';
        if(!empty($key)){
            $where = 'and (u.user_email='.$key.' or u.mobile='.$key.' or u.user_nicename like "%'.$key.'%") ';
        }
        if(!empty($isauth)){
            $where = 'and u.isauth='.$isauth;
        }
        if(empty($sort)){
            $sort = 'addtime DESC';
        }
        $sql = 'SELECT a.one_uid AS ouid,a.addtime,fu.familyid,u.id,u.isauth,u.user_nicename,u.islive,u.user_login,u.avatar,u.bg_img,u.avatar_thumb,u.sex,u.consumption,u.votestotal,u.team_level,u.team_count,u.agent_count,u.team_live_count,u.team_vip_count '
            . 'FROM cmf_agent AS a INNER JOIN cmf_user AS u '
            . 'ON a.uid = u.id '
            . 'LEFT JOIN cmf_family_user AS fu ON a.uid = fu.uid '
            . 'WHERE a.one_uid = '.$uid.' '.$where
            . ' ORDER BY a.'.$sort.' '
            . 'LIMIT '.$pnum.' OFFSET '.$start;
        $list = $this->getORM()->queryAll($sql);
        if($list){
            foreach($list as $k=>$v){
                $list[$k]['level']=getLevel($v['consumption']);
                $level = 1;
                for ($i = 1; $i <= 10; $i++) {
                    if ($list[$k]['level'] <= 10 * $i) {
                        $level = $i;
                        break;
                    }
                }
                if ($v['consumption'] < 1000) {
                    $list[$k]['level'] = '0';
                }
                $list[$k]['level_thumb'] = get_upload_path('images/new_level_v2/level_' . $level . '@2x.png');
                $list[$k]['level_bg_thumb'] = get_upload_path('images/new_level_v2/level_bg_' . $level . '@2x.png');
                if ($v['islive'] == 1 && $v['votestotal'] > 0) {
                    $list[$k]['level_anchor'] = getLevelAnchor($v['votestotal']);
                    $list[$k]['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_' . $v['level_anchor'] . '@3x.png');
                } else {
                    $list[$k]['level_anchor'] = '0';
                    $list[$k]['level_anchor_thumb'] = get_upload_path('images/new_level_v2/level_anchor_1@3x.png');
                }
                $list[$k]['level_team']=$v['team_level'];
                if ($v['level_team'] > 0) {
                    $list[$k]['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_' . $v['level_team'] . '@3x.png');
                } else {
                    $list[$k]['level_team_thumb'] = get_upload_path('images/new_level_v2/level_team_1@3x.png');
                }
                $list[$k]['level_family'] = '0';
                $family_user_count = '0';
                // 公会信息获取
                if ($list[$k]['familyid']) {
                    $family = DI()->notorm->family->select('id,votes')->where('id = ?', $list[$k]['familyid'])->fetchOne();
                    $family_user_count = DI()->notorm->family_user->select('familyid')->where('familyid = ?', $list[$k]['familyid'])->count();
                }
                if ($family && $family_user_count > 10) {
                    $level_family = getLevelFamily($family['votestotal']);
                    $list[$k]['level_family'] = $level_family;
                }
                if ($list[$k]['level_family'] != '0') {
                    $list[$k]['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_' . $list[$k]['level_family'] . '@3x.png');
                } else {
                    $list[$k]['level_family_thumb'] = get_upload_path('images/new_level_v2/level_family_1@3x.png');
                }
                $list[$k]['vip']=getUserVip($v['id']);
                $list[$k]['vip_thumb'] = get_upload_path('images/new_level_v2/VIP1@2x.png');
                $list[$k]['addtime']=date('Y-m-d',$v['addtime']);
                $list[$k]['avatar']=get_upload_path($v['avatar']);
                $list[$k]['avatar_thumb']=get_upload_path($v['avatar_thumb']);
                unset($list[$k]['team_level']);
            }
        }
        return $list;

    }

}
