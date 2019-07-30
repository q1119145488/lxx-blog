<?php 
class WebsocketTest {
    public $server;
    public $redis;
    public function __construct() {
        $this->server = new Swoole\WebSocket\Server("0.0.0.0", 9501);
        //判断目录日志目录是否存在
        $dir = '/www/swoole_log/'.date('Ymd');
        if(!is_dir( $dir ))
            mkdir($dir, 0777);
        $this->server->set(array(
            'daemonize' => true,
            'log_file' => '/www/swoole_log/'.date('Ymd').'/swoole.log',
            
        ));
        $this->redis = new \Redis();
        $this->redis->pconnect('127.0.0.1', '6379');
        echo 'server starts;';
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('close', [$this, 'onClose']);
        $this->server->start();
    }

    /**
     * 监听开启事件的回调
     * 
     * @param  $server swoole_websocket_server
     * @param  $request swoole_http_request
     * @return void
     */
    function onOpen($server, $request){
        echo "server: handshake success with fd{$request->fd}\n";
        //访问的用户fd
        $userFd = $request->fd;
        //链接参数
        $getInfo = $request->get;
        if( isset($getInfo['page']) && $getInfo['page'] == 'login' ){
            //$this->redis->HMSET('user_'.$userFd,'username',)
            //当前在线人数
            $onlineNum = count($this->server->connections);
            //将新连接的用户加入 连接到登录页面 集合中
            $this->redis->SADD('login',$userFd) ;
            //当前在login页面的用户
            $login = $this->redis->SMEMBERS('login');
            //返回停留的login页面的用户，当前在线人数
            foreach ($login as  $fd) {
                $this->server->push($fd, $onlineNum);
            }
            
        }else if(isset($getInfo['room99']) && $getInfo['room99'] == '1'){
            $this->redis->SADD('room1',$userFd);
            echo $userFd.PHP_EOL;
            $username = $getInfo['username'];
            $avatar = $getInfo['avatar'];
            /*$this->redis->HSET('user'.$userFd,'username',$username);
            $this->redis->HSET('user'.$userFd,'avatar',$avatar);*/
            $user = [
                'username'=>$username,
                'avatar'=>$avatar,
                'fd'=>$userFd
            ];
            //当前在聊天室页面的用户
            $roomMember = $this->redis->SMEMBERS('room1');
            print_r($roomMember).PHP_EOL;
            $pushMessage = [
                'code'=>1001,
                'msg'=>'',
                'data'=>$user,
            ];
            //存储用户信息
            $user = json_encode($user);
            $this->redis->set('user'.$userFd,$user);
            //推送消息转json格式
            $pushMessage = json_encode($pushMessage);
            
            //返回连接上的用户的信息
            if($roomMember){
                foreach ($roomMember as $fd) {
                    $this->server->push($fd, $pushMessage);
                }
            }
        }
    }

    /**
     * 监听接收事件的回调
     * 分发任务执行
     * 
     * @param  $server swoole_websocket_server
     * @param  $frame swoole_websocket_frame
     * @return void
     */
    public function onMessage($server, $frame)
    {
        //$receiveMessage = empty($frame->data) ? '' : json_decode($frame->data,true); 
        $message = json_decode($frame->data,true);
        if($message){
            if($message['type'] == 'normal'){
                $receiveMessage = $message['message'];
                $receiveTime = date('H:i',time());
                $userInfo = $this->redis->get('user'.$frame->fd);
                $userInfo = json_decode($userInfo,true);
                $data = [
                    'message'=>$receiveMessage,
                    'time'=>$receiveTime,
                    'username'=>$userInfo['username'],
                    'avatar'=>$userInfo['avatar'],
                ];
            }
            $pushMessage = [
                'code'=>2001,
                'msg'=>'',
                'data'=>$data,
            ];
            $pushMessage = json_encode($pushMessage);
        }
        $roomMember = $this->redis->SMEMBERS( 'room1' );

        foreach ($roomMember as $fd) {
            if($fd != $frame->fd){
                $this->server->push($fd, $pushMessage);
            }
        }
        
    }
    /**
     * 监听关闭事件的回调
     * @param  $server swoole_websocket_server
     * @param  $fd 会话连接ID 
     * @return void
     */
    public function onClose($server, $fd)
    {

        $onlineNum = count($this->server->connections);
        if( $this->redis->Sismember('login',$fd) ){
            if( $this->redis->SREM('login',$fd) ) echo 'delete-login successfully!'.PHP_EOL;
            $login = $this->redis->SMEMBERS('login');
            foreach ($login as  $fd) {
                $this->server->push($fd, $onlineNum-1);
            }
        }else if ( $this->redis->Sismember('room1',$fd) ){
            $userInfo = $this->redis->get( 'user'.$fd );
            $userInfo = json_decode($userInfo,true);
            if( $this->redis->SREM('room1',$fd) ) echo 'delete-room1 successfully!'.PHP_EOL;
            if( $this->redis->del('user'.$fd ) ) echo 'delete-user-info successfully'.PHP_EOL;
            $room = $this->redis->SMEMBERS('room1');
            //推送用户推出房间消息
            $pushMessage = [
                'code'=>1002,
                'msg'=>'',
                'data'=>$userInfo
            ];
            $pushMessage = json_encode($pushMessage);
            foreach ($room as  $fd) {
                $this->server->push($fd, $pushMessage);
            }
        }
        echo "client {$fd} closed\n";
    }


    function onRequest($request, $response){

    }
}


new WebsocketTest();
