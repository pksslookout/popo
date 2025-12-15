<?php
/**
 * 分销
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class AgentController extends HomebaseController {
	
	function index(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=lang('您的登陆状态失效，请重新登陆！');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
		  
		$userinfo=getUserInfo($uid);
		$code=Db::name('agent_code')->where(["uid"=>$uid])->value('code');
		
		if(!$code){
			$code=createCode();
            $ifok=Db::name('agent_code')->where(["uid"=>$uid])->update(array("code"=>$code));
            if(!$ifok){
                Db::name('agent_code')->insert(array('uid'=>$uid,"code"=>$code));
            }
			
		}
        $href=get_upload_path("/wap/index.html#/?agentCode=".$code.'&lang='.$lang);

        $user_information=Db::name('user_information')->where(["id"=>$uid])->find();

        if(empty($user_information['agent_erm'])){
            $qr=scerweima($href,1,$uid);
            cloudUploadLocalFiles($qr,$qr);
            Db::name('user_information')->where(["id"=>$uid])->update(array("agent_erm"=>$qr));
        }else{
            $qr=$user_information['agent_erm'];
        }

        $outputImage = 'upload/agent/'.$uid.'_zh_cn.png';

        if(empty($user_information['agent_url'])){
            // 加载海报背景图
            $qr_img = str_replace('/upload','upload',$qr);
            $poster = imagecreatefrompng('static/appapi/images/agent/bg.png'); // 确保路径正确，并且文件存在
            $qrCodeImage = imagecreatefrompng($qr_img); // 确保路径正确，并且文件存在

            // 获取二维码的尺寸
            $qrWidth = imagesx($qrCodeImage);
            $qrHeight = imagesy($qrCodeImage);

            // 设置二维码在海报上的位置（例如在右下角）
            $x = imagesx($poster) - $qrWidth - 245; // 留出20px的边距
            $y = imagesy($poster) - $qrHeight - 350; // 留出20px的边距

            // 将二维码复制到海报上
            imagecopy($poster, $qrCodeImage, $x, $y, 0, 0, $qrWidth, $qrHeight);

            // 将文字绘制到图片上
            $black = imagecolorallocate($poster, 0, 0, 0);
            $font_path = CMF_ROOT.'public/ttf/wryh.ttf'; // 字体文件的路径
//            if(!file_exists($font_path)){
//                var_dump(1);
//                exit();
//            }
            $font_size = 22;
            $text = lang('邀请码').' '. $code;
            imagettftext($poster, $font_size, 0, 268, 955, $black, $font_path, $text);
            $font_size = 19;
            $text = lang("长按或扫描识别二维码下载");
            imagettftext($poster, $font_size, 0, 225, 1359, $black, $font_path, $text);

            // 保存或显示合并后的图像
            imagejpeg($poster, $outputImage); // 保存文件或使用imagejpeg($poster);直接显示
            imagedestroy($poster); // 释放内存
            imagedestroy($qrCodeImage); // 释放内存

            cloudUploadLocalFiles($outputImage,$outputImage);
            Db::name('user_information')->where(["id"=>$uid])->update(array("agent_url"=>$outputImage));

            unlink($outputImage);

            if(empty($user_information['agent_erm'])){
                unlink($qr);
            }
        }else{
            $outputImage=$user_information['agent_url'];
        }

        $outputImage = get_upload_path($outputImage);
		$code_a=str_split($code);

		$this->assign("code",$code);
		$this->assign("code_a",$code_a);
		$agentinfo=array();

        $qr=get_upload_path($qr);
        $outputImage=get_upload_path($outputImage);
//        $qr='https://test.popolive.net/'.$qr;
		$this->assign("lang",$lang);
		$this->assign("uid",$uid);
		$this->assign("qr",$qr);
		$this->assign("downImg",$outputImage);
		$this->assign("token",$token);
		$this->assign("userinfo",$userinfo);
		$this->assign("agentinfo",$agentinfo);

		return $this->fetch();
	    
	}

	function index3(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $lang=isset($data['lang']) ? $data['lang']: 'zh_cn';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=lang('您的登陆状态失效，请重新登陆！');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}

		$nowtime=time();

		$userinfo=getUserInfo($uid);
		$code=Db::name('agent_code')->where(["uid"=>$uid])->value('code');

		if(!$code){
			$code=createCode();
            $ifok=Db::name('agent_code')->where(["uid"=>$uid])->update(array("code"=>$code));
            if(!$ifok){
                Db::name('agent_code')->insert(array('uid'=>$uid,"code"=>$code));
            }

		}

		$code_a=str_split($code);

		$this->assign("code",$code);
		$this->assign("code_a",$code_a);
		$agentinfo=array();

        /* 是否是分销下级 */
        $users_agent=Db::name("agent")->where(["uid"=>$uid])->find();
		if($users_agent){
			$agentinfo= getUserInfo($users_agent['one_uid']);
		}


		$agentprofit=Db::name("agent_profit")->where(["uid"=>$uid])->find();

		$one_profit=$agentprofit['one_profit'];
		if(!$one_profit){
			$one_profit=0;
		}

		$agnet_profit=array(
			'one_profit'=>number_format($one_profit),
		);

        //统计已邀请好友
        $agent_count=Db::name("agent")->where(["one_uid"=>$uid])->count();

        //统计昨日收益
        $todayMidnight = strtotime("today midnight", $nowtime);
        $tomorrowMidnight = strtotime("tomorrow midnight", $nowtime);
        $agent_sum=Db::name("agent_profit_recode")->where([["one_uid",'=',$uid],['addtime','>=',$tomorrowMidnight],['addtime','<=',$todayMidnight]])->sum('one_profit');

        //统计累计收益
        $agent_all_sum=Db::name("agent_profit")->where(["uid"=>$uid])->sum('one_profit');


		$this->assign("agent_count",$agent_count);
		$this->assign("agent_sum",$agent_sum);
		$this->assign("agent_all_sum",$agent_all_sum);
		$this->assign("lang",$lang);
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("userinfo",$userinfo);
		$this->assign("agentinfo",$agentinfo);
		$this->assign("agnet_profit",$agnet_profit);

		return $this->fetch();

	}

	function index2(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=lang('您的登陆状态失效，请重新登陆！');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}

		$nowtime=time();

		$userinfo=getUserInfo($uid);
		$code=Db::name('agent_code')->where(["uid"=>$uid])->value('code');

		if(!$code){
			$code=createCode();
            $ifok=Db::name('agent_code')->where(["uid"=>$uid])->update(array("code"=>$code));
            if(!$ifok){
                Db::name('agent_code')->insert(array('uid'=>$uid,"code"=>$code));
            }

		}

		$code_a=str_split($code);

		$this->assign("code",$code);
		$this->assign("code_a",$code_a);
		$agentinfo=array();

        /* 是否是分销下级 */
        $users_agent=Db::name("agent")->where(["uid"=>$uid])->find();
		if($users_agent){
			$agentinfo= getUserInfo($users_agent['one_uid']);
		}


		$agentprofit=Db::name("agent_profit")->where(["uid"=>$uid])->find();

		$one_profit=$agentprofit['one_profit'];
		if(!$one_profit){
			$one_profit=0;
		}

		$agnet_profit=array(
			'one_profit'=>number_format($one_profit),
		);

		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("userinfo",$userinfo);
		$this->assign("agentinfo",$agentinfo);
		$this->assign("agnet_profit",$agnet_profit);

		return $this->fetch();

	}
	
	function agent(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=lang('您的登陆状态失效，请重新登陆！');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
		
		$agentinfo=array();
		
		$users_agent=Db::name('agent')->where(["uid"=>$uid])->find();
		if($users_agent){
			$agentinfo=getUserInfo($users_agent['one_uid']);
			
			$code=Db::name('agent_code')->where("uid={$users_agent['one_uid']}")->value('code');
			
			$agentinfo['code']=$code;
			$code_a=str_split($code);

			$this->assign("code_a",$code_a);
		}
	
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);

		$this->assign("agentinfo",$agentinfo);

		return $this->fetch();
	}
	
	function setAgent(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $code=isset($data['code']) ? $data['code']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        $code=checkNull($code);
		
		$rs=array('code'=>0,'info'=>array(),'msg'=>'设置成功');
		
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']=lang('您的登陆状态失效，请重新登陆！');
			echo json_encode($rs);
			exit;
		} 

		if($code==""){
			$rs['code']=1001;
			$rs['msg']=lang('邀请码不能为空');
			echo json_encode($rs);
			exit;
		}
		
		$isexist=Db::name('agent')->where(["uid"=>$uid])->find();
		if($isexist){
			$rs['code']=1001;
			$rs['msg']=lang('已设置');
			echo json_encode($rs);
			exit;
		}
		
		$oneinfo=Db::name('agent_code')->field("uid")->where(["code"=>$code])->find();
		if(!$oneinfo){
			$rs['code']=1002;
			$rs['msg']=lang('邀请码错误');
			echo json_encode($rs);
			exit;
		}
		
		if($oneinfo['uid']==$uid){
			$rs['code']=1003;
			$rs['msg']=lang('不能填写自己的邀请码');
			echo json_encode($rs);
			exit;
		}
		
		$one_agent=Db::name('agent')->where("uid={$oneinfo['uid']}")->find();
		if(!$one_agent){
			$one_agent=array(
				'uid'=>$oneinfo['uid'],
				'one_uid'=>0,
			);
		}else{

			if($one_agent['one_uid']==$uid){
				$rs['code']=1004;
				$rs['msg']=lang('您已经是该用户的上级');
				echo json_encode($rs);
				exit;
			}
		}
		
		$data=array(
			'uid'=>$uid,
			'one_uid'=>$one_agent['uid'],
			'addtime'=>time(),
		);
		Db::name('agent')->insert($data);

        // 计算上级的邀请数量是否等于5 是则开通会员
        $nowtime = time();
        $endtime = 0;
        $one_agent_count=Db::name('agent')->where("one_uid={$one_agent['uid']}")->count();
        if($one_agent_count >= 5){ // 考虑并发 待优化
            $find_vip=Db::name('vip_user')->where("uid={$one_agent['uid']}")->find();
            if(!$find_vip){
                Db::name('vip_user')->insert(['endtime' => $endtime, 'uid' => $one_agent['uid'], 'addtime' => $nowtime]);
            }
        }

		echo json_encode($rs);
		exit;
	}

	function quit(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=lang('您的登陆状态失效，请重新登陆！');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
		
		$isexist=Db::name('agent')->where(["uid"=>$uid])->delete();

		echo json_encode([]);
		exit;
	}
	
	function one(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",lang('您的登陆状态失效，请重新登陆！'));
			$this->display(':error');
			exit;
		} 
		
        $list=Db::name('agent_profit_recode')->field("uid,sum(one_profit) as total")->where(["one_uid"=>$uid])->group("uid")->order("addtime desc")->limit(0,50)->select()->toArray();
		foreach($list as $k=>$v){
			$list[$k]['userinfo']=getUserInfo($v['uid']);
			$list[$k]['total']=NumberFormat($v['total']);
		}
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("list",$list);
		return $this->fetch();
	}

	function one_more(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $p=isset($data['page']) ? $data['page']: '1';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        $p=checkNull($p);
		
		$result=array(
			'data'=>array(),
			'nums'=>0,
			'isscroll'=>0,
		);
		
		if(checkToken($uid,$token)==700){
			echo json_encode($result);
			exit;
		} 
		
		$pnums=50;
		$start=($p-1)*$pnums;
		
		$list=Db::name('agent_profit_recode')->field("uid,sum(one_profit) as total")->where(["one_uid"=>$uid])->group("uid")->order("addtime desc")->limit($start,$pnums)->select()->toArray();
		foreach($list as $k=>$v){
			$list[$k]['userinfo']=getUserInfo($v['uid']);
			$list[$k]['total']=NumberFormat($v['total']);
		}
		
		$nums=count($list);
		if($nums<$pnums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}
		
		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);

		echo json_encode($result);
		exit;
	}

    //扫描app生成的分享二维码显示的下载页面，通过openinstall 自动建立上下级关系
    public function downapp(){
        $data=$this->request->param();
        $code='';
        if(!isset($data['code'])){
            $this->assign("reason",lang('邀请码错误'));
            return $this->fetch(':error');

        }

        $code=$data['code'];
        $code_info= Db::name("agent_code")->where("code='{$code}'")->find();

        if(!$code_info){
            $this->assign("reason",lang('邀请码不存在'));
            return $this->fetch(':error');

        }
        $configpub=getConfigPub();
        $site_name=$configpub['site_name'];
        $configpri=getConfigPri();
        $openinstall_switch=$configpri['openinstall_switch'];
        if(!$openinstall_switch){
            $this->assign("reason",lang('分享通道关闭'));
            return $this->fetch(':error');

        }
        $openinstall_appkey=$configpri['openinstall_appkey'];
        if(!$openinstall_appkey){
            $this->assign("reason",lang('信息配置错误'));
            return $this->fetch(':error');

        }
        $this->assign("site_name",$site_name);
        $this->assign("openinstall_appkey",$openinstall_appkey);
        return $this->fetch();
    }

}