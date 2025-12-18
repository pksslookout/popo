<?php
/**
 * 青少年模式
 */
namespace app\appapi\controller;


use think\Controller;
use think\facade\Db;

class TeenagertimeController extends Controller {
	
	
	public function index(){
		Db::name('user_teenager_time')->delete(true);
	}

}