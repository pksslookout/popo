<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

namespace app\appapi\controller;


use think\Controller;
use think\Db;
/**
 * 支付回调
 */
class PayController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }


    //链 回调
    public function notify_wallet() {

        $request = file_get_contents("php://input");
//        $request = '{\"quantity\":\"0.0101000\",\"rechargeAddress\":\"0x4db5b21cd3303fc2a0e7eee271965c87e00b78fa\",\"receivingAddress\":\"0xb8615500ee614226acf3086e3c583374330f4311\",\"sign\":\"790ee335b99fb7d239bb380d2873914c\",\"contractAddress\":\"0x55d398326f99059ff775485246999027b3197955\",\"chainType\":\"BSC\",\"userId\":\"10\",\"hash\":\"0x4ad472c2fbb9d01df2d2b34fc3eff235fd98735b45f08fe6f92f6367c428ee67\"}';
//        $request = '{\"CallbackCommand\":\"Live.CallbackAfterDestroyRoom\",\"Operator_Account\":\"@TIM#SYSTEM\",\"RoomId\":\"436\",\"EventType\":\"DestroyBySystem\",\"EventTime\":1756986061775,\"RoomInfo\":{\"RoomId\":\"436\",\"RoomName\":\"\u590f\u5b6340\u5ea6\",\"RoomType\":\"Live\",\"Owner_Account\":\"10100015411\",\"IsSeatEnabled\":true,\"TakeSeatMode\":\"ApplyToTake\",\"MaxMemberCount\":100,\"MaxSeatCount\":9,\"CustomInfo\":\"\",\"IsMessageDisabled\":false,\"CoverURL\":\"\",\"ActivityStatus\":0,\"IsPublicVisible\":true,\"ViewCount\":1,\"BackgroundURL\":\"\",\"IsUnlimitedRoomEnabled\":true}}';
//        $request = '{\n\t\"EventGroupId\":\t1,\n\t\"EventType\":\t102,\n\t\"CallbackTs\":\t1757479624820,\n\t\"EventInfo\":\t{\n\t\t\"RoomId\":\t43615,\n\t\t\"EventTs\":\t1757479624,\n\t\t\"EventMsTs\":\t1757479624611\n\t}\n}';
//        $request = '{\n\t\"EventGroupId\":\t1,\n\t\"EventType\":\t102,\n\t\"CallbackTs\":\t1757479811023,\n\t\"EventInfo\":\t{\n\t\t\"RoomId\":\t43588,\n\t\t\"EventTs\":\t1757479811,\n\t\t\"EventMsTs\":\t1757479811018\n\t}\n}';
//        $request = str_replace('\"', '"', $request);
//        $request = str_replace('\n', '
//        ', $request);
//        $request = str_replace('\t', '  ', $request);
        $ip=get_client_ip();
        if($ip!='172.31.25.128'){
            $this->callbacklog('Fail callback request:'.$ip.'::'.json_encode($request));
            $result['code']=400;
            $result['data']=[];
            $result['msg']='Fail';
            echo json_encode($result);
            exit();
        }
        $this->callbacklog('callback request:'.$ip.'::'.json_encode($request));

        $request = json_decode($request, true);

        $key = 'FUi6zVhAD2a8uC68';
        $hash = $request['hash'];
        $quantity = $request['quantity'];
        $receivingAddress = $request['receivingAddress'];
        $rechargeAddress = $request['rechargeAddress'];
        $contractAddress = $request['contractAddress'];
        $chainType = $request['chainType'];
        $uid = $request['userId'];
        $sign = $hash.'='.$quantity.'='.$receivingAddress.'='.$rechargeAddress.'='.$contractAddress.'='.$uid.'='.$chainType;

        $sign = md5($sign.'='.$key);

        if($request['sign'] != $sign){
            $result['code']=400;
            $result['data']=[];
            $result['msg']='Fail signature error';
            echo json_encode($result);
            exit();
        }

        Db::startTrans();
        try {
            $user = Db::name("user")->where("id='{$uid}'")->lock(true)->find();

            if(!empty($user)){
                $where = ['trade_no' => $contractAddress];
                $orderinfo=Db::name("charge_user_usdt")->where($where)->find();

                if(!$orderinfo){
                    $orderno = $uid.'_'.date('YmdHis').rand(100,999);
                    $orderinfo=[
                        'uid'=>$uid,
                        'touid'=>$uid,
                        'money'=>$quantity,
                        'usdt'=>$quantity,
                        'usdt_give'=>0,
                        'orderno'=>$orderno,
                        'trade_no'=>$contractAddress,
                        'status'=>0,
                        'type'=>4,
                        'remark'=>$chainType,
                        'addtime'=>time(),
                    ];

                    Db::name("charge_user_usdt")->insert($orderinfo);
                }

                if($orderinfo['status']!=0){
                    Db::rollback();
                    $result['code']=201;
                    $result['data']=[];
                    $result['msg']='success';
                    echo json_encode($result);
                    exit;
                }
                $usdt=$orderinfo['usdt']+$orderinfo['usdt_give'];
                Db::name("user")->where("id='{$orderinfo['touid']}'")->setInc("usdt",$usdt);
                Db::name("user_information")->where("id='{$orderinfo['touid']}'")->setInc("usdt_charge",$usdt);

                /* 更新 订单状态 */
                $data['status']=1;
                $where = ['trade_no' => $contractAddress];
                Db::name("charge_user_usdt")->where($where)->update($data);
                Db::commit();

                $result['code']=200;
                $result['data']=[];
                $result['msg']='success';
                echo json_encode($result);
                exit;
            }else{
                Db::rollback();
                $result['code']=404;
                $result['data']=[];
                $result['msg']='fail';
                echo json_encode($result);
                exit;
            }
        } catch (\Exception $e) {
            Db::rollback();
//            throw $e;
            $result['code']=400;
            $result['data']=[];
            $result['msg']='fail';
            echo json_encode($result);
            exit;
        }

    }

    public function callbacklog($msg){
        file_put_contents(CMF_ROOT.'data/walletlog/walletback_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 :'.$msg."\r\n",FILE_APPEND);
    }
}


