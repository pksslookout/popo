<?php

/**
 * 短视频--评论
 */

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class UseradvertcomController extends AdminbaseController
{


    public function index()
    {

        $data = $this->request->param();
        $map = [];

        $advertid = isset($data['advertid']) ? $data['advertid'] : '';
        if ($advertid != '') {
            $map[] = ['advertid', '=', $advertid];
        }

        $lists = DB::name("user_advert_comment")
            ->where($map)
            ->order('id desc')
            ->paginate(20);


        $lists->appends($data);
        $page = $lists->render();

        $this->assign('lists', $lists);
        $this->assign("page", $page);

        return $this->fetch();
    }

}
