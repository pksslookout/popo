<?php
/**
 * 青少年模式
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\facade\Db;

class TeenagertimeController extends HomebaseController {
	
	
	public function index(){
		Db::name('user_teenager_time')->delete(true);
	}

}