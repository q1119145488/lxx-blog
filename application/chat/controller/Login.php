<?php
namespace app\chat\controller;
use think\Controller;
class Login extends Controller
{
    public function index()
    {
    	/*$generator = new \Nubs\RandomNameGenerator\Alliteration();
		$randomName = $generator->getName();*/
		$randomName = 'user'.rand(1,1000);
        return $this->fetch('',[
        	'randomName'=>$randomName	
        ]);
    }
}
