<?php
namespace app\common\model;
use think\Model;

/**
* 
*/
class Tags extends Model
{
	
	public function addtags($data)
	{

      	return $this->insert($data);
        
	}

	public function getAllTags(){
		$order = [
            'id' =>'desc',
        ];
        $data = [
            'status' => '1',
        ];
        $result =  $this->where($data)
                        ->order($order)
                        ->select();
                    
        return $result;
	}
    public function getArticleTags($articleId){
        $where = [
            'articleId' => $articleId
        ];

        return $this->where($where)
                        ->select();
        }

    public function removetags($articleId){
        $where = [
            'articleId' => $articleId
        ];
        return $this->where($where)
                    ->delete();
    }
    public function getTopTags($num = 10){
        $order = [
            'id' =>'desc',
        ];
        return $this->where('status',1)
                    ->limit($num)
                    ->field("DISTINCT(tags),id")
                    ->order($order)
                    ->select();
    }
}