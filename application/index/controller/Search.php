<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Route;
class Search extends Controller
{
    public function index()
    {
    	$keyword = Request::instance()->param('keyword','','htmlspecialchars,trim');
    	$url = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:url('index/index');
    	if($keyword == '')
    		return mAlert('搜索关键字不能为空',$url);
    	$list = model('article')->articleSearch($keyword);
 		$topTags = model('Tags')->getTopTags(10);


        return $this->fetch('',[
        		'list'=>$list,
        		'topTags'=>$topTags,
        		'controller'=>'search',
        		'title' => $keyword.'--刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx'
        	]);
    }
}
