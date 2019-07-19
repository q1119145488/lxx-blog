<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        //首页文章列表    
        $Article = model('Article')->Article(10);
        //首页文章数目
        $articleNum = model('Article')->indexNum();
        $tags = model('Tags')->getAllTags();
        $topTags = model('Tags')->getTopTags(10);
        //访问量
        $ip = getIp();
        $time = date('y-m-d');
        $device = $_SERVER['HTTP_USER_AGENT'];
        //获取访问时间，若不是在当天访问的，则访问量加一
        $create_time = model('pageView')->where(['ip'=>$ip])->value('create_time');
        if($create_time){
            if(date('y-m-d',$create_time) != $time)
                model('pageView')->insert(['ip'=>$ip,'create_time'=>time(),'device'=>$device]);
        }else
            model('pageView')->insert(['ip'=>$ip,'create_time'=>time(),'device'=>$device]);

        return $this->fetch('',[
        			'Article' =>$Article,
        			'controller' => strtolower(request()->controller() ),
                    'tags' =>$tags,
                    'articleNum' =>$articleNum,
                    'title' => 'GoAheadlxx',
                    'topTags'=>$topTags
        		]);
    }

    public function test(){
        return $this->fetch();
    }

    function getOS()
    {
        /*$ua = $_SERVER['HTTP_USER_AGENT'];//这里只进行IOS和Android两个操作系统的判断，其他操作系统原理一样
        if (strpos($ua, 'Android') !== false) {//strpos()定位出第一次出现字符串的位置，这里定位为0
            preg_match("/(?<=Android )[\d\.]{1,}/", $ua, $version);
           $version = $version[0];
        } elseif (strpos($ua, 'iPhone') !== false) {
            preg_match("/(?<=CPU iPhone OS )[\d\_]{1,}/", $ua, $version);
            $version = str_replace('_', '.', $version[0]);
        } elseif (strpos($ua, 'iPad') !== false) {
            preg_match("/(?<=CPU OS )[\d\_]{1,}/", $ua, $version);
            $version = str_replace('_', '.', $version[0]);
        } 
        if(!isset($version))
            $version = 'pc';*/
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (stripos($user_agent, "iPhone")!==false) {
        $brand = 'iPhone';
    } else if (stripos($user_agent, "SAMSUNG")!==false || stripos($user_agent, "Galaxy")!==false || strpos($user_agent, "GT-")!==false || strpos($user_agent, "SCH-")!==false || strpos($user_agent, "SM-")!==false) {
        $brand = '三星';
    } else if (stripos($user_agent, "Huawei")!==false || stripos($user_agent, "Honor")!==false || stripos($user_agent, "H60-")!==false || stripos($user_agent, "H30-")!==false) {
        $brand = '华为';
    } else if (stripos($user_agent, "Lenovo")!==false) {
        $brand = '联想';
    } else if (strpos($user_agent, "MI-ONE")!==false || strpos($user_agent, "MI 1S")!==false || strpos($user_agent, "MI 2")!==false || strpos($user_agent, "MI 3")!==false || strpos($user_agent, "MI 4")!==false || strpos($user_agent, "MI-4")!==false) {
        $brand = '小米';
    } else if (strpos($user_agent, "HM NOTE")!==false || strpos($user_agent, "HM201")!==false) {
        $brand = '红米';
    } else if (stripos($user_agent, "Coolpad")!==false || strpos($user_agent, "8190Q")!==false || strpos($user_agent, "5910")!==false) {
        $brand = '酷派';
    } else if (stripos($user_agent, "ZTE")!==false || stripos($user_agent, "X9180")!==false || stripos($user_agent, "N9180")!==false || stripos($user_agent, "U9180")!==false) {
        $brand = '中兴';
    } else if (stripos($user_agent, "OPPO")!==false || strpos($user_agent, "X9007")!==false || strpos($user_agent, "X907")!==false || strpos($user_agent, "X909")!==false || strpos($user_agent, "R831S")!==false || strpos($user_agent, "R827T")!==false || strpos($user_agent, "R821T")!==false || strpos($user_agent, "R811")!==false || strpos($user_agent, "R2017")!==false) {
        $brand = 'OPPO';
    } else if (strpos($user_agent, "HTC")!==false || stripos($user_agent, "Desire")!==false) {
        $brand = 'HTC';
    } else if (stripos($user_agent, "vivo")!==false) {
        $brand = 'vivo';
    } else if (stripos($user_agent, "K-Touch")!==false) {
        $brand = '天语';
    } else if (stripos($user_agent, "Nubia")!==false || stripos($user_agent, "NX50")!==false || stripos($user_agent, "NX40")!==false) {
        $brand = '努比亚';
    } else if (strpos($user_agent, "M045")!==false || strpos($user_agent, "M032")!==false || strpos($user_agent, "M355")!==false) {
        $brand = '魅族';
    } else if (stripos($user_agent, "DOOV")!==false) {
        $brand = '朵唯';
    } else if (stripos($user_agent, "GFIVE")!==false) {
        $brand = '基伍';
    } else if (stripos($user_agent, "Gionee")!==false || strpos($user_agent, "GN")!==false) {
        $brand = '金立';
    } else if (stripos($user_agent, "HS-U")!==false || stripos($user_agent, "HS-E")!==false) {
        $brand = '海信';
    } else if (stripos($user_agent, "Nokia")!==false) {
        $brand = '诺基亚';
    } else {
        $brand = '其他';
    }
        return $brand;
    }
}
