<?php
namespace app\common\model;
use think\Model;

class Article extends Model
{
    public function add($data) {
        $data['status'] = 1;
      	$this->save($data);
        return $this->id;   
    }

    public function ArticleList($num,$status='2'){
        $order = [
            'id' =>'desc',
        ];
        $data = [
            'status' => ['neq',$status],
        ];
        $result =  $this->where($data)
        				->order($order)
                    	->paginate($num);
                    
        return $result;
    }
    public function Article($num){
        $order = [
            'id' =>'desc',
        ];
        $data = [
            'status' => '1',
        ];
        $result =  $this->where($data)
                        ->order($order)
                        ->paginate($num);
                    
        return $result;
    }


    public function indexNum(){
        $status = [
            'status' => '1',
        ];
        return $this->where($status)
                     ->count();
    }
    public function search($keyword){
       
        $sql = "SELECT `title`,`id` FROM `article` WHERE  `status` = '1' AND `title` LIKE '%$keyword%' LIMIT 0,5 ";
         return  $this->query($sql);

         
        //$where['title'] = array('like',"%{$keyword}%");
       /* return $this->where($where)
             ->limit(0,5)
             ->select();*/
        //return $this->getLastSql();
    }
    function getArticleBytag($tag){
        return $this->alias('a')
                    ->join('tags b','a.id = b.articleId')
                    ->where(['b.tags'=>$tag,'a.status'=>1,'b.status'=>1])
                    ->field('a.title,a.id,a.time')
                    ->order('time','desc')
                    ->select();
    }
    //文章搜索
    function articleSearch($keyword){
        return $this->where(['status'=>1,'title'=>['like','%'.$keyword.'%']])
                    ->order('time','desc')
                    ->field('title,id,description,time')
                    ->select();
    }
}
?>