<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Session;
class Message extends Controller
{
		public function captcha(){
			$request = Request::instance();
			if( request()->isPost() ){
				$data = input('post.');
				$addResult = Session::get('addResult','think');
				$code = htmlentities($data['code']);
				
				if( $addResult != $code ){
					return show('0','wrong');
				}else{
					return show('1','OK');
				}
			}
		}
}