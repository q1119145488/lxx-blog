<?php
namespace app\index\controller;
use think\Controller;
class Read extends Controller
{
    public function index()
    {
    	$topTags = model('Tags')->getTopTags(10);
        return $this->fetch('',[
        		'controller'=>'read',
        		'title' => 'Read----刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx',
        		'topTags'=>$topTags,
        	]);
    }
}
