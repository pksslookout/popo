<?php

/**
 * 转化数据库保存的文件路径，为可以访问的url
 */
function get_upload_path($file){
    if($file==''){
        return $file;
    }
    if(strpos($file,"http")===0){
        return $file;
    }else if(strpos($file,"/")===0){
        $configpub=getConfigPub();
        $filepath= $configpub['site'].$file;
        return $filepath;
    }else{
        $configpri=getConfigPri();
        if($configpri['cloudtype']=='2'){
            $filepath= $configpri['qcloud_scheme'].'://'.$configpri['qcloud_host_cdn'].'/'.$file;
            return $filepath;
        }else{
            $style='';
            $storage = Storage::instance();
            return $storage->getImageUrl($file, $style);
        }
    }
}