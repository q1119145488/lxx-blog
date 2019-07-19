<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
class Message extends Base
{
    public function index()
    {
    	$Message = model('Message')->MessageList(['neq',2]);

        return $this->fetch('',[
        		'Message' =>$Message,
                'title' => 'Message List',
        		'controller' => strtolower(request()->controller() )
        	]);
    }
    function detail($id){
        $detail = model('message')->get($id);
        return $this->fetch('',[
                'title'=>'留言详情',
                'detail'=>$detail,
                'controller' => strtolower(request()->controller() )
            ]);
    }
    public function status(){
        $data = Request::instance()->param();
        $res = model('Message')->save(['status'=>$data['status']],['id'=>$data['id']]);
		 if($res) {
            return alert('状态修改成功',$_SERVER["HTTP_REFERER"],6,3);
        }else {
            return alert('状态修改失败',$_SERVER["HTTP_REFERER"],6,3);
        }
    }
}
