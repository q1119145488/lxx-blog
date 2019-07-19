<?php
namespace app\api\controller;
use think\Controller;
use think\Request;

class Index extends Controller
{
		public function like(){
			if( request()->isPost() ){
				$data = input('post.');

				$articleId = htmlentities($data['articleId']);
				$request = Request::instance();
				$ip = $request->ip();

				$where = [
					'articleId' => $articleId,
					'ip' => $ip
				];
				$selectResult = model('like')->isLike($where);

				if( $selectResult ){
					return show(2,'had-liked');
				}else{
					$addResult = model('like')->add($where);
						$likeNum = model('Like')->likeNum(['articleId'=>$articleId]);
						return show(1,'success',$likeNum);
			}
		}
	}


	public function search(){
		if( request()->isPost() ){
				$data = input('post.');

				$keyword = htmlentities($data['keyword']);
				if( $keyword != '' ){
					$search = model('Article')->search($keyword);

				if( $search ){
					return show(1,'success',$search);
				}else{
					return show(0,'error');
					}
				}				
		}
	}
}