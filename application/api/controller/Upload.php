<?php
namespace app\api\controller;
use think\Controller;
use think\Request;

class Upload extends Controller{

	public function imageUpload(){


		$file = $this->request->file('images');
		//$file = input('post.');
		$info = $file->move( ROOT_PATH . 'uploads' );
		
		/*{
		  "success": true/false,
		  "msg": "error message", # optional
		  "file_path": "[real file path]"
		}*/
		$path = ROOT_PATH.'uploads/'.$info->getSaveName();
		if( $info ){
			$return = [
				'success' => 'true',
				'mes' => '',
				'file_path' => $path
			];

			echo json_encode($return);
		}else{

			$return = [
				'success' => 'false',
				'mes' => $file->getError(),
				'file_path' => $path
			];

			echo json_encode($return);
		}


	}

	public function wangImageUpload(){


		$file = $this->request->file('image');
		$info = $file->move( ROOT_PATH . 'uploads' );
		
		/*{
		  "success": true/false,
		  "msg": "error message", # optional
		  "file_path": "[real file path]"
		}*/
		$path ='/uploads/'.$info->getSaveName();
		$return = [
			'errno'=>0,
			'data' => [$path],
		];
		asd(json_encode($return));



	}
}