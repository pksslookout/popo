<?php
/**
 * 下载页面
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;

class DownController extends Controller {

	function index(){       
		return $this->fetch();
	}

}