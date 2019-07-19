<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
use phpmailer\Email;
class Message extends Controller
{


    public function index()
    {
    	$request = Request::instance();
    	if(request()->isPost()){
    		$data = input('post.');

            $dataInsert['name'] = $data['name'] ;

            $addResult = Session::get('addResult','think');


    		$dataInsert['email'] = htmlentities($data['email']);
    		$dataInsert['content'] = htmlentities($data['content']);
    		$dataInsert['time'] = time();   		
			$dataInsert['ip'] = $request->ip();
            $captcha = htmlentities($data['captcha']);

            if( $addResult != $captcha ){
                return alert('验证码错误',$_SERVER["HTTP_REFERER"],5,3);
            }


			$res = model('Message')->add($dataInsert);
			if(!$res){
			 	return alert('留言失败',$_SERVER["HTTP_REFERER"],5,3);
			 }else{

                $to = 'www.xianxiang@qq.com';
                $title = '你的博客终于有人留言啦，请注意查收';
                $content = htmlspecialchars_decode($dataInsert['content']);
                try{
                    $send = \phpmailer\Email::send($to,$title, $content);
                     return alert('留言成功！若博主回复您，您填写的邮箱则会收到通知！',$_SERVER["HTTP_REFERER"],6,5);
                }catch(\Exception $e){
                    return alert('留言失败',$_SERVER["HTTP_REFERER"],5,3);
                }
			 	
			 }

    	}else{
            //留言列表
            $message = model('Message')->MessageList(['eq',1]);
            $topTags = model('Tags')->getTopTags(10);
            //$addResult = Session::get('addResult','think');
    		return $this->fetch('',[
                    'message' => $message,
                    'controller'=>'message',
                    'title' => 'Message--刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx',
                    //'captcha' => $addResult,
                    'topTags'=>$topTags,
                ]);
    	}
        
    }

    public static function getCaptcha( $w='120',$h='30'){
            header( 'Content-Type: image/png' );
            ob_clean ();
            $im = imagecreatetruecolor( $w, $h );

            // Create some colors
            $white = imagecolorallocate($im, 255, 255, 255);
            $grey = imagecolorallocate($im, 128, 128, 128);
            $black = imagecolorallocate($im, 0, 0, 0);
            imagefilledrectangle($im, 0, 0, 399, 29, $white);
            $addFirst = mt_rand(0,99);
            $addSecond = mt_rand(0,99);
            $text = "$addFirst + $addSecond= ";
            $addResult = $addFirst + $addSecond;

            Session::set('addResult',$addResult);

            imagettftext($im, 20, 0, 11, 25, $grey, "./Arial.ttf", $text);// Add some shadow to the text

            imagettftext($im, 20, 0, 10, 24, $black, "./Arial.ttf", $text);// Add the text


            // Using imagepng() results in clearer text compared with imagejpeg()
            imagepng($im);
            imagedestroy($im);

        }
}
