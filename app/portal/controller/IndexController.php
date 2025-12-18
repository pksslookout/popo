<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use think\Controller;
use think\facade\View;

class IndexController extends Controller
{

    // 首页
    public function index()
    {

        /* redis缓存开启 */
        connectionRedis();

        $siteInfo = getConfigPub();

        if(isset($siteInfo['sina_icon'])){
            $siteInfo['sina_icon']=get_upload_path($siteInfo['sina_icon']);
        }
        if(isset($siteInfo['qq_icon'])){
            $siteInfo['qq_icon']=get_upload_path($siteInfo['qq_icon']);
        }
        if(isset($siteInfo['apk_ewm'])){
            $siteInfo['apk_ewm']=get_upload_path($siteInfo['apk_ewm']);
        }
        if(isset($siteInfo['ipa_ewm'])){
            $siteInfo['ipa_ewm']=get_upload_path($siteInfo['ipa_ewm']);
        }
        if(isset($siteInfo['wechat_ewm'])){
            $siteInfo['wechat_ewm']=get_upload_path($siteInfo['wechat_ewm']);
        }
        if(isset($siteInfo['qr_url'])){
            $siteInfo['qr_url']=get_upload_path($siteInfo['qr_url']);
        }else{
            $siteInfo['qr_url']='';
        }

        View::share('site_info', $siteInfo);
        return $this->fetch();
    }
    
    public function scanqr() {
    	return $this->fetch();
    }

}

