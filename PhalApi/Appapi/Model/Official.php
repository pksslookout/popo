<?php

class Model_Official extends PhalApi_Model_NotORM {

	public function getOfficialNews()
    {

        $info = DI()->notorm->official
            ->select("id,title,introduction,pushtime")
            ->order("pushtime desc")
            ->fetchOne();

        return $info;
    }

	public function getOfficialList($p) {
        if($p<1){
            $p=1;
        }
        $pnums=20;
        $start=($p-1)*$pnums;

        $info=DI()->notorm->official
            ->select("id,title,introduction,pushtime,url")
            ->order("pushtime desc")
            ->limit($start,$pnums)
            ->fetchAll();

		return $info;
	}

	public function getOfficialInfo($id) {

        $info=DI()->notorm->official
            ->select('*')
            ->where('id=?',$id)
            ->fetchOne();
        if($info){
            $info['pushtime'] = date('Y-m-d H:i:s',$info['pushtime']);
            $info['addtime'] = date('Y-m-d H:i:s',$info['addtime']);
        }

		return $info;
	}

}
