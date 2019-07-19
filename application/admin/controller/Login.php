<?php 
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Request;


class Login extends Controller{
	public $account;
	public function index()
    {

    	if( request()->isPost() ){
    		$data = input('post.');
    		$username = htmlentities($data['username']);
    		$password = htmlentities($data['password']);

    		if($username && $password){
    			$user = model('User')->getUserByUsername($username);
    			if(!$user || $user['status'] != 1 ){
    				return alert('用户不存在',$_SERVER["HTTP_REFERER"],5,3);
    			}
    			if( md5($data['password'].$user->code) != $user->password ){
		       		return alert('密码错误',$_SERVER["HTTP_REFERER"],5,3);
		       	}
		       	$request = Request::instance();
				$ip = $request->ip();
		       	model('User')->updateById(['last_login_time' => time(),'last_login_ip'=> $ip],$user->Id);
		       	//ini_set('session.gc_maxlifetime',10);

		       	session('user',$user,'admin');
                //设置cookie
                $encode = json_encode([$user->Id,$user->code]);
                $encode = base64_encode($encode);
                setcookie('userInfo1',$encode,time()+86400*7,'/admin');
		       	return alert('登陆成功',url('index/index'),6,4);

    		}
    	}else{
    		$user = session('user','','admin') ? session('user','','admin') : '';
	    	if(  empty($user) &&  empty($user->Id)  ){
		    		return $this->fetch('',[
	        		'title' => 'Home',
	        		'controller' => strtolower(request()->controller() )
	        	]);
	    	}else{
	    		return alert('正在转至后台...',url('index/index'),6,4);
	    	}
    		
    	}
        
    }   
    public function logincheck(){
    	if( request()->isPost() ){
    		$data = input('post.');
    		$username = htmlentities($data['username']);
    		$password = htmlentities($data['password']);

    		if($username && $password){
    			$user = model('User')->getUserByUsername($username);
    			if(!$user || $user['status'] != 1 ){
    				return alert('用户不存在',$_SERVER["HTTP_REFERER"],5,3);
    			}
    			if( md5($data['password'].$user->code) != $user->password ){
		       		return alert('密码错误',$_SERVER["HTTP_REFERER"],5,3);
		       	}
		       	$request = Request::instance();
				$ip = $request->ip();
		       	model('User')->updateById(['last_login_time' => time(),'last_login_ip'=> $ip],$user->Id);

		       	session('user',$user,'admin');
		       	return alert('登陆成功',url('index/index'),6,4);

    		}
    	}
    }


    public function logout(){
        //清楚session
        session(null, 'admin');
        return alert('退出成功',url('Login/index'),6,4);
       // return $this->redirect(url('login/index'));
    }
		
}
?>