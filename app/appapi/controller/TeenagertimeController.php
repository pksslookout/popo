<?php
/**
 * 青少年模式
 */
namespace app\appapi\controller;


use think\Controller;
use think\facade\Db;

class TeenagertimeController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	
	public function index(){
		Db::name('user_teenager_time')->delete(true);
	}

}