<?php

/**
 * 上传
 */

class Api_Upload extends PhalApi_Api {

	public function getRules() {
		return array(

		);
	}
	
	/**
	 * 获取云存储方式、获取七牛云存储上传验证token字符串等信息、获取腾讯云存储相关配置信息
	 * @desc 用于获取云存储方式、获取七牛云存储上传验证token字符串等信息、获取腾讯云存储相关配置信息
	 * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
	 */
	public function getCosInfo(){
		$rs=array("code"=>0,"msg"=>"","info"=>array());

		$configpri=getConfigPri();
		$cloudtype=$configpri['cloudtype'];

		if(!$cloudtype){
			$rs['code']=1001;
            $rs['msg']=T("无指定存储方式");
            return $rs;
		}

		$qiniuInfo=array(
            'qiniuToken'=>'',
            'qiniu_domain'=>'',
            'qiniu_zone'=>''  //华东:qiniu_hd 华北:qiniu_hb  华南:qiniu_hn  北美:qiniu_bm   新加坡:qiniu_xjp 不可随意更改，app已固定好规则
        );

        $qcloudInfo=array(
            'qcloud_images_folder'=>'',
            'qcloud_video_folder'=>'',
            'qcloud_appid'=>'',
            'qcloud_region'=>'',
            'qcloud_bucket'=>'',
        );

        if($cloudtype=='1'){ //七牛云存储

        	$qiniuToken=$this->getQiniuToken();
        	$space_host=DI()->config->get('app.Qiniu.space_host');
        	$region= DI()->config->get('app.Qiniu.region');
        	$qiniu_zone='';

            switch ($region) {
                case 'z0': //华东
                    $upload_url='up.qiniup.com';
                    $qiniu_zone='qiniu_hd';
                    break;
                case 'z1': //华北
                    $upload_url='up-z1.qiniup.com';
                    $qiniu_zone='qiniu_hb';
                    break;
                case 'z2': //华南
                    $upload_url='up-z2.qiniup.com';
                    $qiniu_zone='qiniu_hn';
                    break;
                case 'na0': //北美
                    $upload_url='up-na0.qiniup.com';
                    $qiniu_zone='qiniu_bm';
                    break;
                case 'as0': //东南亚
                    $upload_url='up-as0.qiniup.com';
                    $qiniu_zone='qiniu_xjp';
                    break;

                default:
                    $upload_url='up.qiniup.com';
                    break;
            }

        	$qiniuInfo=array(
	            'qiniuToken'=>$qiniuToken,
	            'qiniu_domain'=>$space_host,
	            'qiniu_region'=>$region,
	            'qiniu_upload_url'=>$upload_url,
	            'qiniu_zone'=>$qiniu_zone  //华东:qiniu_hd 华北:qiniu_hb  华南:qiniu_hn  北美:qiniu_bm   新加坡:qiniu_xjp 不可随意更改，app已固定好规则
	        );

        }else if($cloudtype=='2'){ //腾讯云
            $qcloudInfo['qcloud_images_folder']='upload/images/';//腾讯云图片存储目录
            $qcloudInfo['qcloud_video_folder']='upload/video/';//腾讯云视频存储目录
            $qcloudInfo['qcloud_appid']=$configpri['qcloud_app_id'];//腾讯云视频APPID
            $qcloudInfo['qcloud_region']=$configpri['qcloud_region'];//腾讯云视频地区
            $qcloudInfo['qcloud_bucket']=$configpri['qcloud_bucket'];//腾讯云视频存储桶
        }


        switch ($cloudtype) {
        	case '1':
        		$cloudtype='qiniu';
        		break;
        	case '2':
        		$cloudtype='qcloud';
        		break;
        	
        }

        $rs['info'][0]['qiniuInfo']=$qiniuInfo;
        $rs['info'][0]['qcloudInfo']=$qcloudInfo;
        $rs['info'][0]['cloudtype']=$cloudtype;

        return $rs;

	}
	
    /**
     * 获取获取七牛token
     * @desc 用于获取获取七牛token
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
	private function getQiniuToken(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $token = DI()->qiniu->getQiniuToken();
        $rs['info'][0]['token']=$token ;
        return $rs;
	}
}
