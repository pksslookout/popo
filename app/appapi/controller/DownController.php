<?php
/**
 * 下载页面
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;

class DownController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }

	function index(){       
		return $this->fetch();
	}

}