<?php
namespace app\common\model;
use think\Model;

/**
* 
*/
class Like extends Model
{
	
	public function isLike($where){
        
        $result = $this->where($where)
                       ->find();
        return $result;
    }
    public function add($data){

        $this->save($data);
    }

    public function likeNum($articleId){

    	return $this->where($articleId)
                     ->count();
    }

}