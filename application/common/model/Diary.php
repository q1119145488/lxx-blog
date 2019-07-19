<?php
namespace app\common\model;
use think\Model;

/**
* 
*/
class Diary extends Model
{
	
    public function add($data){

        return $this->save($data);
    }


    public function getAllDiaries(){
        $where = [
            'status'=> '1'
        ];
        $order = [
            'time' => 'desc'
        ];
        return $this->where($where)
                    ->order($order)
                    ->select();
    }

    public function updateDiary($where,$updataData){
        return $this->where($where)
             ->update($updataData);
    }

}