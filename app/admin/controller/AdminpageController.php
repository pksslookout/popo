<?php

/**
 * 页面管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminpageController extends AdminbaseController {
    function index(){
        $data = $this->request->param();
        $map=[];

        $id=isset($data['id']) ? $data['id']: '';
        if($id!=''){
            $map[]=['id','=',$id];
        }

        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['title','like','%'.$keyword.'%'];
        }
        $map[]=['type','=',1];

    	$lists = Db::name("admin_post")
                ->where($map)
                ->order("list_order DESC")
                ->paginate(20);

        $lists->each(function($v,$k){
            $v['url'] = '/appapi/page/news?id='.$v['id'];
            return $v;
        });
        
        $lists->appends($data);
        $page = $lists->render();

        $configpub=getConfigPub();

    	$this->assign('configpub', $configpub);

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

    	return $this->fetch();
    }
    function edit(){
        $id   = $this->request->param('id', 0, 'intval');
        $data=Db::name('admin_post')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }
        $data['content'] = html_entity_decode($data['content']);
        $data['content_en'] = html_entity_decode($data['content_en']);
        $language_content = get_language_translate('admin_post', 'content', $id);
        foreach ($language_content as $k=>$v){
            $v["value"] = html_entity_decode($v["value"]);
            $language_content[$k] = $v;
        }
        $this->assign('list_language_translate', get_language_translate('admin_post', 'title', $id));
        $this->assign('list_language_translate_content', $language_content);
        $this->assign('data', $data);
        return $this->fetch();
    }

    function editPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['title']=trim($data['title']);
            $data['title_en']=trim($data['title_en']);
            $title=$data['title'];
            $title_en=$data['title_en'];

            $id=$data['id'];

            if($title==""){
                $this->error("标题名称不能为空");
            }

            if($title_en==""){
                $this->error("Title不能为空");
            }

            $isexit=DB::name('admin_post')->where([['id','<>',$id],['title','=',$title]])->find();
            if($isexit){
                $this->error('该页面标题已存在');
            }

            $admin_id = cmf_get_current_admin_id();
            $userinfo=getUserInfo($admin_id);
            $data['uid']=$admin_id;
            $data['user_nicename']=$userinfo['user_nicename'];
            $data['addtime']=time();
            // 修改多国语言
            $language = $data['language'];
            $language_content = $data['language_content'];
            unset($data['language']);
            unset($data['language_content']);

            $rs = DB::name('admin_post')->update($data);
            if(!$id){
                $this->error("修改失败！");
            }

            update_language_translate('admin_post', 'title', $id, $language);
            update_language_translate('admin_post', 'content', $id, $language_content);
            $action="修改页面：{$id}";
            setAdminLog($action);

            $this->success("修改成功！");
        }
    }

}
