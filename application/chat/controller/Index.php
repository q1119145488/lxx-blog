<?php
namespace app\chat\controller;
use think\Controller;
class Index extends Controller
{
	function index(){
        $user = session('user');
        if(!$user)
            return mAlert('请先登录',url('login/index'));

        $redis = new \Redis();
        $redis->pconnect('127.0.0.1', '6379');
        //当前在线人数
        $onlineUser = $redis->SMEMBERS( 'room1' ); 
        $roomMember = [];
        if($onlineUser){
            foreach ($onlineUser as $key => $userFd) {
                $userInfo = $redis->get('user'.$userFd);
                if( $userInfo ) $userInfo = json_decode($userInfo,true);
                $roomMember[] = $userInfo;
            }
        }
    	return $this->fetch('',[
        	'user'=>$user,
            'roomMember'=>$roomMember,
            'ip'=>'103.74.192.84'
        ]);
    }

    public function room($id)
    {
        return $this->fetch('',[
        		
        ]);
    }
    
}
