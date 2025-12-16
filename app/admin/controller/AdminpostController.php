<?php

/**
 * 文章管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class AdminpostController extends AdminbaseController {
    function index(){
        $data = $this->request->param();
        $map=[];

        $admin_term_id=isset($data['admin_term_id']) ? $data['admin_term_id']: '';
        if($admin_term_id!=''){
            $map[]=['admin_term_id','=',$admin_term_id];
        }
        
        $id=isset($data['id']) ? $data['id']: '';
        if($id!=''){
            $map[]=['id','=',$id];
        }
        $map[]=['type','=',0];

        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['title','like','%'.$keyword.'%'];
        }

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
        
    	$this->assign("admin_term", $this->getAdminTerm());

    	return $this->fetch();
    }
    
    function del(){

        $data = $this->request->param();

        if (isset($data['id'])) {
            $id = $data['id']; //获取删除id

            $rs = DB::name('admin_post')->where("id={$id}")->delete();
            if(!$rs){
                $this->error("删除失败！");
            }
            delete_language_translate('admin_post', 'title', $id);
            delete_language_translate('admin_post', 'content', $id);
            $action="删除文章：{$id}";
            setAdminLog($action);


        } elseif (isset($data['ids'])) {
            $ids = $data['ids'];

            $rs = DB::name('admin_post')->where('id', 'in', $ids)->delete();
            if(!$rs){
                $this->error("删除失败！");
            }
            delete_language_translate_all('admin_post', 'title', $ids);
            delete_language_translate_all('admin_post', 'content', $ids);

            $action="删除文章：".implode(",",$ids);
            setAdminLog($action);

        }
        
        $this->success("删除成功！",url("adminpost/index"));
    }

    protected function getAdminTerm($k=''){
        $list=Db::name("admin_term")
            ->order("id asc")
            ->column('name','id');

        if($k==''){
            return $list;
        }
        return isset($list[$k])?$list[$k]:'';
    }

    //排序
    public function listOrder() {

        $model = DB::name('admin_post');
        parent::listOrders($model);

        $action="更新文章排序";
        setAdminLog($action);

        $this->success("排序更新成功！");

    }

    function add(){
        $this->assign('list_language', get_language_all());
        $this->assign("admin_term", $this->getAdminTerm());
        return $this->fetch();
    }

    function addPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['title']=trim($data['title']);
            $data['title_en']=trim($data['title_en']);
            $title=$data['title'];
            $title_en=$data['title_en'];

            if($title==""){
                $this->error("标题名称不能为空");
            }

            if($title_en==""){
                $this->error("Title不能为空");
            }

            $isexit=DB::name('admin_post')->where(["title"=>$title])->find();
            if($isexit){
                $this->error('该文章标题已存在');
            }

            $admin_id = cmf_get_current_admin_id();
            $userinfo=getUserInfo($admin_id);
            $data['uid']=$admin_id;
            $data['user_nicename']=$userinfo['user_nicename'];
            $data['addtime']=time();
            // 新增多国语言
            $language = $data['language'];
            $language_content = $data['language_content'];
            unset($data['language']);
            unset($data['language_content']);

            $id = DB::name('admin_post')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            insert_language_translate('admin_post', 'title', $id, $language);
            insert_language_translate('admin_post', 'content', $id, $language_content);
            $action="添加文章：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
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
        $this->assign("admin_term", $this->getAdminTerm());
        $this->assign('list_language_translate', get_language_translate('admin_post', 'title', $id));
        $language_content = get_language_translate('admin_post', 'content', $id);
        foreach ($language_content as $k=>$v){
            $v["value"] = html_entity_decode($v["value"]);
            $language_content[$k] = $v;
        }
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
                $this->error('该文章标题已存在');
            }

            $admin_id = cmf_get_current_admin_id();
            $userinfo=getUserInfo($admin_id);
            $data['uid']=$admin_id;
            $data['user_nicename']=$userinfo['user_nicename'];
            $data['addtime']=time();
            // 修改多国语言
            if(!empty($data['language_content'])){
                $language_content = $data['language_content'];
                unset($data['language_content']);
            }
            if(!empty($data['language'])){
                $language = $data['language'];
                unset($data['language']);
            }

            $rs = DB::name('admin_post')->update($data);
            if(!$id){
                $this->error("修改失败！");
            }
            if(!empty($data['language_content'])){
                update_language_translate('admin_post', 'content', $id, $language_content);
            }
            if(!empty($data['language'])){
                update_language_translate('admin_post', 'title', $id, $language);
            }
            $action="修改文章：{$id}";
            setAdminLog($action);

            $this->success("修改成功！");
        }
    }

}
