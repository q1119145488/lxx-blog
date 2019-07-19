<?php
namespace app\admin\controller;
use think\Controller;
class Diary extends Base
{
    public function index()
    {
    	$diary = model('Diary')->getAllDiaries();
    	
        return $this->fetch('',[
        		'diary' => $diary,
        		'title' => 'Diary',
        		'controller' => strtolower(request()->controller() )
        	]);
    }
}
