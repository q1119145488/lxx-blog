<?php
namespace app\admin\controller;
use think\Controller;

class Base extends Controller{
	public $account;
	public function _initialize() {
	  	//判断用户是否登陆
        
	  	$isLogin= $this->isLogin();
	  	if(!$isLogin){
	  		$this->error('请先登录！',url('login/index'));
	  	}
    }
    public function isLogin(){
        
    	//获取session值
    	$user = $this->getLoginUser();
    	if($user && $user->Id)
    		return true;
    	else{
            if($this->hasCookie())
                return true;
            else
    		  return false;
    	}
    }
    public function getLoginUser(){
    	if(!$this->account){
    		$this->account = session('user','','admin');
    	}
    	return $this->account;
    }
    public function hasCookie(){
        if($_COOKIE['userInfo1']){
            $decode = $_COOKIE['userInfo1'];
            $decode = base64_decode($decode);
            $decode = json_decode($decode);
            if(empty($decode))
                return false;   
            $user = model('user')->where(['Id'=>$decode[0],'code'=>$decode[1]])->find();
            if(!$user)    
                return false;
            session('user',$user,'admin');
            return 1;
        }
        
    }
}

?>