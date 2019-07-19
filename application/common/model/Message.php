<?php
namespace app\common\model;
use think\Model;

class Message extends Model
{

    public function add($data) {
        $data['status'] = 0;
        $res=$this->save($data);
        return $res;
    }

    public function MessageList($status){
        $order = [
            'id' =>'desc',
        ];
        $data = [
            'status' => $status,
        ];
        $result =  $this->where($data)
        				->order($order)
                    	->paginate(7);
                    
        return $result;
    }

}
?>