<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class Tags extends Controller
{
    public function index()
    {
    	$tag = Request::instance()->param('tag','','htmlspecialchars,trim');
    	$list = model('article')->getArticleBytag($tag);
    	$topTags = model('Tags')->getTopTags(10);
        return $this->fetch('',[
        		'list'=>$list,
        		'tag'=>$tag,
        		'title' => $tag.'--刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx',
                'controller'=>'tags',
        		'topTags'=>$topTags
        	]);
    }
}
