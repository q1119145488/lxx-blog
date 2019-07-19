<?php
namespace app\api\controller;
use think\Controller;
use think\Request;

class Diary extends Controller
{
		public function add(){
			if( request()->isPost() ){
				$data = input('post.');
				$data['diary'] = htmlentities($data['diary']);
				$data['time'] = time();
				$res = model('Diary')->add($data);
				$data['time'] = date("m-d",$data['time'] );

				if($res){
					$diary = model('Diary')->getAllDiaries();
					for($i = 0;  $i < count($diary);$i++ ){
						$diary[$i]['time'] = date('m-d',$diary[$i]['time']);
					}
					return show(1,'success',$diary);
				}
				else{
					return show(0,'failed');
				}
				
		}
	}

	public function edit(){
		if( request()->isPost() ){
			$data = input('post.');
			if($data['Str'] != ' '){
				$data['diary'] = htmlentities($data['Str']);
				$data['id'] = htmlentities($data['ID']);
				$data['time'] = time();

				$where = ['id' =>$data['id']];
				$updateData = [
					'time' => $data['time'],
					'diary' => $data['diary']
				];
				$updateupdate = model('Diary')->updateDiary($where,$updateData);
				if($update > 0) echo json_encode('success');
			}else{
				return false;
			}
		}		
	}

	public function delete(){
		if( request()->isPost() ){
			$data = input('post.');
			$data['id'] = htmlentities($data['id']);
			$data['status'] = htmlentities($data['status']);


			$where = ['id' =>$data['id']];
			$updateData = ['status' => $data['status']];


			$update = model('Diary')->updateDiary($where,$updateData);

			if($update > 0){
				 return show(1,'success');
				}else{
					return show(0,'failed');
				}

		}
	}


}