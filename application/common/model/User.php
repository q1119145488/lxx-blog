<?php
namespace app\common\model;
use think\Model;

/**
* 
*/
class User extends Model
{
	
	 public function getUserByUsername($username){
        if(!$username){
            exception('用户名不合法');
        }
        return $this->where(['username'=>$username])
                    ->find();
    }

     public function updateById($data,$id){
        // allowField 过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }

}