<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Session;
class Room extends Controller
{	
	function login(){
		$request = request::instance();
		$data =  $request->post('','','htmlspecialchars,trim');

		$userName = $data['userName'];
		$avatar = $data['avatar'];
		$ip = $request->ip();
		$device = $_SERVER['HTTP_USER_AGENT'];

		$user = [
			'username'=>$userName,
			'avatar'=>$avatar,
			'ip'=>$ip,
			'device'=>$device,
			'create_time'=>time(),
		];
		if( model('roomUser')->save($user) ){
			$user['id'] = model('roomUser')->id;
			session('user',$user);
			$result = [
				'msg'=>'创建用户成功',
				'code'=>'10001'
			];
			setcookie('avatar',$avatar,time()+86400,'/room');
			setcookie('username',$userName,time()+86400,'/room');
		}else{
			$result = [
				'msg'=>'创建用户失败',
				'code'=>'10000'
			];
		}
		return $result;
	}
	function userList(){
		$redis = new \Redis();
        $redis->pconnect('127.0.0.1', '6379');
		$onlineUser = $redis->SMEMBERS( 'room1' ); 
		$roomMember = [];
        if($onlineUser){
            foreach ($onlineUser as $key => $userFd) {
                $userInfo = $redis->get('user'.$userFd);
                if( $userInfo ) $userInfo = json_decode($userInfo,true);
                $roomMember[] = $userInfo;
            }
        }
        $result = [
        	'code'=>3001,
        	'msg'=>'',
        	'data'=>$roomMember,
        	'num'=>count($roomMember),
        ];
        return $result;
	}	
}