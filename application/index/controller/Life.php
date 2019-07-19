<?php
namespace app\index\controller;
use think\Controller;
class Life extends Controller
{
    public function index()
    {
    	$topTags = model('Tags')->getTopTags(10);
        return $this->fetch('',[
        		'controller'=>'life',
        		'title' => 'Life----刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx',
        		'topTags'=>$topTags,
        	]);
    }
}
