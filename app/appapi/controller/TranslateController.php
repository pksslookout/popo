<?php
/**
 * 翻译语言生成文本
 */
namespace app\appapi\controller;


use think\Controller;
use think\Db;
use cmf\lib\Upload;
use Google\Cloud\Translate\V2\TranslateClient;

class TranslateController extends Controller {

    protected function initialize()
    {
        /* redis缓存开启 */
        connectionRedis();
    }
	
	public function index(){

        $translate = new TranslateClient([
            'ket' => ''
        ]);
        var_dump(1);
	    
	}

}