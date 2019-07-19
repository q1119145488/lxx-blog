<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status) {
    if($status == 1) {
        $str = "<span class='label label-success radius'>正常</span>";
    }else {
        $str = "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

function pagination($obj) {
    if(!$obj) {
        return '';
    }
    // 优化的方案
    $params = request()->param();
    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';
}

function alert($msg='',$url='',$icon='',$time=3){
    
    $str='<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>
        <script src="https://cdn.bootcss.com/layer/3.0.3/layer.js"></script>
        ';//加载jquery和layer

        $str.='<script>
        $(function(){
          layer.msg("'.$msg.'",{icon:"'.$icon.'",title:"提示",time:"'.($time*1000).'"});
          setTimeout(function(){
            self.location.href="'.$url.'"
            },2000)
          });
        </script>';//主要方法

    return $str;
}
function asd($param){
    print_r($param);
    exit();
}
function mAlert($msg,$url){
    return "<script>alert('".$msg."');window.location.href ='".$url."';</script>";
}

function getIp(){
       if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
        else
        $ip = "unknown";
        return($ip);
}
function createQrcodeNologo($url,$level='H',$size='10'){

        Vendor("phpqrcode.phpqrcode");
        $object = new \QRcode(); 

        $path = './public/';
        $openid= date("Ymdhis",time()).rand(0,999);
        if(!is_dir($path)) { 
            mkdir($path);
        }
        $file = "qrcode/nologo/qrcode_" .$openid. ".png";
        $filename = $path.$file;

        $object -> png($url, $filename, $level, $size, 0);
        return ltrim($filename,'.');
    }