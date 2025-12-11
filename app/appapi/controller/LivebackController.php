<?php
/**
 * 直播回放
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class livebackController extends HomebaseController {
	
	/* 
		回调数据格式
		{
				"channel_id": "2121_15919131751",
				"end_time": 1473125627,
				"event_type": 100,
				"file_format": "flv",
				"file_id": "9192487266581821586",
				"file_size": 9749353,
				"sign": "fef79a097458ed80b5f5574cbc13e1fd",
				"start_time": 1473135647,
				"stream_id": "2121_15919131751",
				"t": 1473126233,
				"video_id": "200025724_ac92b781a22c4a3e937c9e61c2624af7",
				"video_url": "http://200025724.vod.myqcloud.com/200025724_ac92b781a22c4a3e937c9e61c2624af7.f0.flv"
		}
	*/
	function index_rtc()
    {
        $request = file_get_contents("php://input");
//        $request = '{\"CallbackCommand\":\"Live.CallbackAfterDestroyRoom\",\"Operator_Account\":\"@TIM#SYSTEM\",\"RoomId\":\"436\",\"EventType\":\"DestroyBySystem\",\"EventTime\":1756986061775,\"RoomInfo\":{\"RoomId\":\"436\",\"RoomName\":\"\u590f\u5b6340\u5ea6\",\"RoomType\":\"Live\",\"Owner_Account\":\"10100015411\",\"IsSeatEnabled\":true,\"TakeSeatMode\":\"ApplyToTake\",\"MaxMemberCount\":100,\"MaxSeatCount\":9,\"CustomInfo\":\"\",\"IsMessageDisabled\":false,\"CoverURL\":\"\",\"ActivityStatus\":0,\"IsPublicVisible\":true,\"ViewCount\":1,\"BackgroundURL\":\"\",\"IsUnlimitedRoomEnabled\":true}}';
//        $request = '{\n\t\"EventGroupId\":\t1,\n\t\"EventType\":\t102,\n\t\"CallbackTs\":\t1757479624820,\n\t\"EventInfo\":\t{\n\t\t\"RoomId\":\t43615,\n\t\t\"EventTs\":\t1757479624,\n\t\t\"EventMsTs\":\t1757479624611\n\t}\n}';
//        $request = '{\n\t\"EventGroupId\":\t1,\n\t\"EventType\":\t102,\n\t\"CallbackTs\":\t1757479811023,\n\t\"EventInfo\":\t{\n\t\t\"RoomId\":\t43588,\n\t\t\"EventTs\":\t1757479811,\n\t\t\"EventMsTs\":\t1757479811018\n\t}\n}';
//        $request = str_replace('\"', '"', $request);
//        $request = str_replace('\n', '
//        ', $request);
//        $request = str_replace('\t', '  ', $request);
        $this->callbacklog('callback request:'.json_encode($request));
        $result['ActionStatus']='OK';
        $result['ErrorInfo']='';
        $result['ErrorCode']=0;
        $data = json_decode($request, true);

        if(!$data){
            $this->callbacklog("request para json format error");
            echo json_encode($result);
            exit;
        }
        if(!empty($data['CallbackCommand'])&&$data['CallbackCommand']=='Live.CallbackAfterDestroyRoom'){
            $this->stopRoom($data['RoomInfo']['Owner_Account'],$data['RoomId'],$data);
        }
        if(!empty($data['EventType'])&&$data['EventType']==102){
            $this->stopRoom($data['EventInfo']['RoomId']);
        }
        echo json_encode($result);
        exit;
    }
    function index(){
		$request = file_get_contents("php://input");
        
        $this->callbacklog('callback request:'.json_encode($request));
		$result = array( 'code' => 0 );    
		$data = json_decode($request, true);

		if(!$data){
			$this->callbacklog("request para json format error");
			$result['code']=4001;
			echo json_encode($result);	
			exit;
		}
		
		if(/* array_key_exists("t",$data) && array_key_exists("sign",$data) &&  */array_key_exists("event_type",$data)  && array_key_exists("stream_id",$data))
		{
			// $check_t = $data['t'];
			// $check_sign = $data['sign'];
			$event_type = $data['event_type'];
			$stream_id = $data['stream_id'];
		}else {
			$this->callbacklog("request para error");
			$result['code']=4002;
			echo json_encode($result);	
			exit;
		}
		/* $md5_sign = $this-> GetCallBackSign($check_t);
		if( !($check_sign == $md5_sign) ){
			$this->callbacklog("check_sign error:" . $check_sign . ":" . $md5_sign);
			$result['code']=4003;
			echo json_encode($result);	
			exit;
		}      */   
		
		if($event_type == 100){
			/* 回放回调 */
			if(array_key_exists("video_id",$data) && 
					array_key_exists("video_url",$data) &&
					array_key_exists("start_time",$data) &&
					array_key_exists("end_time",$data) ){
						
				$video_id = $data['video_id'];
				$video_url = $data['video_url'];
				$start_time = $data['start_time'];
				$end_time = $data['end_time'];
			}else{
				$this->callbacklog("request para error:回放信息参数缺少" );
				$result['code']=4002;
				echo json_encode($result);	
				exit;
			}
		}     
		$ret=0;
		if($event_type == 0){        	
			/* 状态回调 断流 */
			//$ret=$this->stopRoom('',$stream_id);
			$this->upOfftime(1,'',$stream_id);
		}elseif ($event_type == 1){
            /* 推流 */
			//$ret = $this->dao_live->callBackLiveStatus($stream_id,1);
            $this->upOfftime(0,'',$stream_id);
		}elseif ($event_type == 100){
			//$duration = $end_time - $start_time;
			//if ( $duration > 60 ){ 	
				$data=array(
					"video_url"=>$video_url,
					//"duration"=>$duration,
					//"file_id"=>$video_id,
				);								
				Db::name("live_record")->where(["stream"=>$stream_id])->update($data);
			//}else {
			//	$ret = 0;
			//	$this->callbacklog("tape duration too short:" . strval($duration) ."|" . $stream_id . "|" . $video_id);
			//}
			
		}	
		$result['code']=$ret; 
		echo json_encode($result);	
		exit;

	}
	
	public function GetCallBackSign($txTime){
		$config=getConfigPri();
        
		$md5_val = md5($config['live_push_key'] . strval($txTime));
		return $md5_val;
	}
	
	public function callbacklog($msg){
		 file_put_contents(CMF_ROOT.'data/livelog/liveback_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 :'.$msg."\r\n",FILE_APPEND);
	}
    
	public function upOfftime($isoff=1,$uid='',$stream=''){
        $where['islive']=1;
		if($uid){
            $where['uid']=$uid;
		}else{
            $where['stream']=$stream;
		}
        $data=[
            'isoff'=>$isoff,
            'offtime'=>0,
        ];
        if($isoff==1){
            $data['offtime']=time();
        }
        
        $info=Db::name('live')->where($where)->update($data);
        
        return 0;
    }
	
	public function stopRoom($uid='',$stream='',$data=[]){

        if(!empty($data['RoomInfo']['ViewCount'])){
            $ViewCount=$data['RoomInfo']['ViewCount'];
        }
        if(!empty($data['Operator_Account'])){
            $Operator_Account=$data['Operator_Account'];
        }
        if(!empty($data['EventType'])){
            $EventType=$data['EventType'];
        }
        $where['islive']=1;
		if($uid){
            $where['uid']=$uid;
		}else{
            $where['stream']=$stream;
		}
			
		$info=Db::name('live')->field('uid,showid,starttime,title,province,city,stream,lng,lat,type,type_val,liveclassid')->where($where)->find();

		if($info){
			Db::name('live')->where(['stream'=>$info['stream']])->delete();
            
            $uid=$info['uid'];
            $stream=$info['stream'];
            
			$nowtime=time();
			$info['endtime']=$nowtime;
			$info['time']=date("Y-m-d",$info['showid']);
            $where2['uid']=['neq',$uid];
            $where2['touid']=$uid;
            $where2['showid']=$info['showid'];
            
			$votes=Db::name('user_coinrecord')
				->where($where2)
				->sum('totalcoin');
			$info['votes']=0;
			if($votes){
				$info['votes']=$votes;
			}
			$nums=zSize('user_'.$stream);			
			hDel("livelist",$uid);
			delcache($uid.'_zombie');
			delcache($uid.'_zombie_uid');
			delcache('attention_'.$uid);
			delcache('user_'.$stream);
			$info['nums']=$nums;
            if(!empty($ViewCount)){
                $info['view_nums']=$ViewCount;
            }
            if(!empty($Operator_Account)){
                $info['operator_uid']=$Operator_Account;
            }
            if(!empty($EventType)){
                $info['event_type']=$EventType;
            }
			$result=Db::name('live_record')->insert($info);
            
            /* 游戏处理 */
			$game=Db::name('game')
				->where(["stream"=>$stream, "liveuid"=>$uid, "state"=>0])
				->find();
            
			if($game){
				$total=Db::name('gamerecord')
					->field("uid,sum(coin_1 + coin_2 + coin_3 + coin_4 + coin_5 + coin_6) as total")
					->where(['gameid'=>$game['id']])
					->group('uid')
					->select()
                    ->toArray();
				foreach($total as $k=>$v){                    
					Db::name('user')->where(['id','=',$v['uid']])->setInc('coin',$v['total']);
                    
					$insert=array("type"=>'1',"action"=>'20',"uid"=>$v['uid'],"touid"=>$v['uid'],"giftid"=>$game['id'],"giftcount"=>1,"totalcoin"=>$v['total'],"addtime"=>$nowtime );
					Db::name('user_coinrecord')->insert($insert);
				}

				Db::name('game')->where(['id' => $game['id']])->update(array('state' =>'3','endtime' => time() ) );
                    
				$brandToken=$stream."_".$game["action"]."_".$game['starttime']."_Game";
				delcache($brandToken);
			}
		}else{
            $where=[];
            $where['stream']=$stream;
            $info=Db::name('live_record')->field('uid,stream')->where($where)->find();
            if($info){
                $info=[];
                if(!empty($ViewCount)){
                    $info['view_nums']=$ViewCount;
                }
                if(!empty($Operator_Account)){
                    $info['operator_uid']=$Operator_Account;
                }
                if(!empty($EventType)){
                    $info['event_type']=$EventType;
                }
                if(!empty($info)){
                    Db::name('live_record')->where($where)->update($info);
                }
            }
        }
		return 0;
	}

	public function stopRoomT($uid=''){

        $Operator_Account='SYS_T';
        $EventType=102;

        $where['islive']=1;
        $where['uid']=$uid;

		$info=Db::name('live')->field('uid,showid,starttime,title,province,city,stream,lng,lat,type,type_val,liveclassid')->where($where)->find();

		if($info){
			Db::name('live')->where(['uid'=>$uid])->delete();

            $uid=$info['uid'];
            $stream=$info['stream'];

			$nowtime=time();
			$info['endtime']=$nowtime;
			$info['time']=date("Y-m-d",$info['showid']);
            $where2['uid']=['neq',$uid];
            $where2['touid']=$uid;
            $where2['showid']=$info['showid'];

			$votes=Db::name('user_coinrecord')
				->where($where2)
				->sum('totalcoin');
			$info['votes']=0;
			if($votes){
				$info['votes']=$votes;
			}
			$nums=zSize('user_'.$stream);
			hDel("livelist",$uid);
			delcache($uid.'_zombie');
			delcache($uid.'_zombie_uid');
			delcache('attention_'.$uid);
			delcache('user_'.$stream);
			$info['nums']=$nums;
            if(!empty($Operator_Account)){
                $info['operator_uid']=$Operator_Account;
            }
            if(!empty($EventType)){
                $info['event_type']=$EventType;
            }
			$result=Db::name('live_record')->insert($info);

            /* 游戏处理 */
			$game=Db::name('game')
				->where(["stream"=>$stream, "liveuid"=>$uid, "state"=>0])
				->find();

			if($game){
				$total=Db::name('gamerecord')
					->field("uid,sum(coin_1 + coin_2 + coin_3 + coin_4 + coin_5 + coin_6) as total")
					->where(['gameid'=>$game['id']])
					->group('uid')
					->select()
                    ->toArray();
				foreach($total as $k=>$v){
					Db::name('user')->where(['id','=',$v['uid']])->setInc('coin',$v['total']);

					$insert=array("type"=>'1',"action"=>'20',"uid"=>$v['uid'],"touid"=>$v['uid'],"giftid"=>$game['id'],"giftcount"=>1,"totalcoin"=>$v['total'],"addtime"=>$nowtime );
					Db::name('user_coinrecord')->insert($insert);
				}

				Db::name('game')->where(['id' => $game['id']])->update(array('state' =>'3','endtime' => time() ) );

				$brandToken=$stream."_".$game["action"]."_".$game['starttime']."_Game";
				delcache($brandToken);
			}
		}else{
            $where=[];
            $where['uid']=$uid;
            $info=Db::name('live_record')->field('uid,stream')->where($where)->find();
            if($info){
                $info=[];
                $info['operator_uid']='SYS_T';
                $info['event_type']=102;
                if(!empty($info)){
                    Db::name('live_record')->where($where)->update($info);
                }
            }
        }
		return 0;
	}
    
    
    /* 定时处理关播-允许短时间 断流续推 */
    public function uplive(){
        $notime=time();
        
        $offtime=$notime - 30;
        
        $where=[];
        $where[]=['islive','=','1'];
        $where[]=['isvideo','=','0'];
        $where[]=['isoff','=','1'];
        $where[]=['offtime','<',$offtime];
        $list=Db::name("live")->where($where)->select();
        $list->each(function($v,$k){
            $this->stopRoom('',$v['stream']);
        });
        // file_put_contents(CMF_ROOT.'data/uplive_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 :'.'OK'."\r\n",FILE_APPEND);
        echo 'OK';
        exit;
    }

}