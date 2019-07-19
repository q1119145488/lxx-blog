<?php
namespace app\api\controller;
use think\Controller;
use think\Session;
class Article extends Controller
{
	public function tags(){
		$data = input('post.');

		//$data['tags'] = input('tags');
		//$data['id'] = substr(strval(rand(10000,19999)),1,4);
		if( $data['tags'] !=''  ){

			//tags存入数据库
			/*$id = model('Tags')->add($data);
			if( $id) {
				$return  = model('Tags')->get($id);
				return show(1,'success', $return);
			}else{
				return show(0,'error');
			}*/
			
			if( session('tag') != '' ){
				//session::clear();exit();
				$length = session('tagLength');

				$arr[] = session('tag');

				for( $i = 0; $i <= $length; $i++ ){
					
					if($length < 2 && $i != $length ){
						$return[$i] = $arr[$i];

					}elseif($length >= 2  && $i != $length){

						$return[$i] = $arr[0][$i];


					}else{
						$return[$i] = $data;
					}

				}

				session::set('tag',$return);
				session::set('tagLength',count($return));


				return show(2,'succuss',$return);
			}else{
				


				session::set('tag',$data);
				session::set('tagLength','1');


				if(session('tag.tag') !== ''){
					//return show(1,'succuss',count(session('tag')) );
					return show(1,'succuss',session('tag') );
				}
			}
		}else{
			return show(-1,'error');
		}
		
		
	}

	public function tagsRemove(){

		if(request()->isPost()){

			$data = input('post.');
			if( $data['status'] == 1){
				Session::clear('think');
				if( session('tags') == '' )
					return show(1,'succuss' );
			}

		}
	}

	function addArticle(){
		$data = input('post.');
		$tagsData = session('tag');
		if(empty($tagsData)){
			$result = [
				'code'=>'1000',
				'msg'=>'没有添加标签',
			];
			return $result;
		}
		$addData['title'] = htmlentities($data['title']);
		$addData['content'] = htmlentities($data['content']);
        $addData['description'] = htmlentities($data['description']);
		$addData['category'] = htmlentities($data['category']);
		//$addData['tags'] = htmlentities( $data['tags'] );
		$addData['time'] = time();
		$Id = model('Article')->add($addData);
		if(!$Id){
		 	$result = [
   				'code'=>1000,
   				'msg'=>'失败'
   			];
   			return $result;
		 }else{
            //创建qrcode
            $qrcode = createQrcodeNologo('http://lxx66.cn/post/id/'.$Id);
            model('Article')->save(['qrcode'=>$qrcode],['id'=>$Id]);
            $tagsData = session('tag');
            $tagsCount = count($tagsData);
            if($tagsCount == 1 && $tagsCount!= ''){
                $tags =[
                    'tags' => $tagsData['tags'],
                    'articleId' => $Id,
                    'id' =>substr(strval(rand(10000,19999)),1,4)
                ];
                 $res = model('tags')->addtags($tags);
            }else{
                for( $i = 0; $i < $tagsCount; $i++ ){
                    $tags[$i] = [
                        'tags' => $tagsData[$i]['tags'],
                        'articleId' => $Id,
                        'id' =>substr(strval(rand(10000,19999)),1,4),
                    ];

                       $res = model('tags')->addtags($tags[$i]);
                    }
             }
               	if($res) Session::clear('tag');                 
	 	   			$result = [
	 	   				'code'=>1001,
	 	   				'msg'=>'添加成功'
	 	   			];
	 	   			return $result;
		    }
	}

	public function editArticle(){
        
        $data = input('post.');
        $data1['id'] = htmlentities($data['id']);
        $data1['title'] = htmlentities($data['title']);
        $data1['content'] = htmlentities($data['content']);
        $data1['description'] = htmlentities($data['description']);
        $data1['category'] = htmlentities($data['category']);

        $data1['time'] = time();
        $data1['qrcode'] = createQrcodeNologo('http://lxx66.cn/post/id/'.$data['id']);
        
        $tagsData = session('tag');
        if(!empty($tagsData)){

        	$delete = model('Tags')->removetags( $data['id'] );
        	$tagsCount = count($tagsData);
        	if($tagsCount == 1 && $tagsCount!= ''){
                $tags =[
                    'tags' => $tagsData['tags'],
                    'articleId' => $data1['id'],
                    'id' =>substr(strval(rand(10000,19999)),1,4)
                ];
                 $res = model('tags')->addtags($tags);
            }else{
                for( $i = 0; $i < $tagsCount; $i++ ){
                    $tags[$i] = [
                        'tags' => $tagsData[$i]['tags'],
                        'articleId' => $data1['id'],
                        'id' =>substr(strval(rand(10000,19999)),1,4),
                    ];

                       $res = model('tags')->addtags($tags[$i]);
                    }
             }
        }
         Session::clear('tag');

         //print_r($data);exit();
        $result = model('Article')->update($data1);

        if($result)
        	$result = [
   				'code'=>1001,
   				'msg'=>'修改成功'
   			];
   			return $result;          

    }
}