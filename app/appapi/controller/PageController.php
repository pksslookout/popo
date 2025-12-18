<?php
/**
 * 关于我们
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;
use cmf\lib\Upload;

class PageController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	public function news(){
		$data = $this->request->param();
        $id=isset($data['id']) ? $data['id']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';

        $info=Db::name('admin_post')->where(["id"=>$id])->find();
        if($lang=='en'){
            $info['title'] = $info['title_'.$lang];
        }else{
            if($lang!='zh_cn'){
                $translate = get_language_translate('admin_post', 'title', $id);
                foreach ($translate as $value) {
                    if($value['code']==$lang){
                        $info['title'] = $value['value'];
                    }
                }
            }
        }
        $info['content']=htmlspecialchars_decode($info['content']);
        if($lang=='en'){
            $info['content'] = htmlspecialchars_decode($info['content_'.$lang]);
        }else{
            if($lang!='zh_cn'){
                $translate = get_language_translate('admin_post', 'content', $id);
                foreach ($translate as $value) {
                    if($value['code']==$lang){
                        $info['content'] = htmlspecialchars_decode($value['value']);
                    }
                }
            }
        }
        $this->assign("info",$info);
        $this->assign("lang",$lang);
		return $this->fetch();
	    
	}
	public function questions(){
		$data = $this->request->param();
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $this->assign("lang",$lang);
		return $this->fetch();

	}

}