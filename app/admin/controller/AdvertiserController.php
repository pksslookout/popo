<?php

/**
 * 广告管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use cmf\lib\Upload;

class AdvertiserController extends AdminbaseController {


    function index(){
        $data = $this->request->param();
        $map=[];

        $user_nicename=isset($data['user_nicename']) ? $data['user_nicename']: '';
        if($user_nicename!=''){
            $map[]=['user_nicename','like',"%".$user_nicename."%"];
        }

        $certification_entity=isset($data['certification_entity']) ? $data['certification_entity']: '';
        if($certification_entity!=''){
            $map[]=['certification_entity','like',"%".$certification_entity."%"];
        }

        $phone = isset($data['phone']) ? $data['phone']: '';
        if($phone!=''){
            $map[]=['phone','like',"%".$phone."%"];
        }

    	$lists = Db::name("advertiser")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);

        
        $lists->appends($data);
        $page = $lists->render();

        $lists->each(function($v,$k){
            $v['qualification_picture_one']=get_upload_path($v['qualification_picture_one']);
            $v['qualification_picture_two']=get_upload_path($v['qualification_picture_two']);
            return $v;
        });

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        

    	return $this->fetch();
    }

    /* 通过与拒绝 */
    public function setStatus(){
        $id = $this->request->param('id', 0, 'intval');
        $is_status = $this->request->param('is_status', 0, 'intval');
        if($id){

            $data['handlingtime'] = time();
            $data['is_status'] = $is_status;
            $result=DB::name("advertiser")->where(['id'=>$id])->update($data);
            if($result){

                $action="通过与拒绝广告主申请：广告主ID({$id}),状态({$is_status})";
                setAdminLog($action);

                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->error('数据传入失败！');
        }

    }
    
}
