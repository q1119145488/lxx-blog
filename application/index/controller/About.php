<?php
namespace app\index\controller;
use think\Controller;
class About extends Controller
{
    public function index()
    {
    	$diary = model('Diary')->getAllDiaries();

    	for($i = 0;  $i < count($diary);$i++ ){
			$diary[$i]['time'] = date('m-d',$diary[$i]['time']);
		}
        $topTags = model('Tags')->getTopTags(10);
        return $this->fetch('',[
                'controller'=>'about',
        		'diary' => $diary,
                'topTags'=>$topTags,
                'title' => 'Diary-----刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx'
        	]);
    }
}
