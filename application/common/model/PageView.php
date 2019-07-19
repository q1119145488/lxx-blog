<?php
namespace app\common\model;
use think\Model;

/**
* 
*/
class PageView extends Model
{
	
	public function pvList($num){
        $order = [
            'id' =>'desc',
        ];
        $result =  $this->order($order)
                    	->paginate($num);
                    
        return $result;
    }
    function todayPv($time = ''){
    	return $this->field("FROM_UNIXTIME(create_time,'%y-%m-%d') as `today`")
    				->where('today',$time)
    				->select();
    }

}