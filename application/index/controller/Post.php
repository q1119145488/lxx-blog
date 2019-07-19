<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class Post extends Controller
{
    public function index()
    {
    	$id = Request::instance()->param('id','','strip_tags');


    	$likeNum = model('Like')->likeNum(['articleId'=>$id]);//点赞数
    	$post = model('Article')->get($id);//文章相关内容
        $topTags = model('Tags')->getTopTags(10);//热门标签
        //文章点击量
        $ip = getIp();
        //查找访问记录中的访问时间
        $create_time = model('click')->where(['ip'=>$ip,'articleId'=>$id])->value('create_time');
        //如果存在访问时间，比较是否超过了24小时，如果是的话，则再插入一条访问记录,
        if($create_time){
            if(time()-$create_time >= 86400)
                model('click')->insert(['ip'=>$ip,'articleId'=>$id,'create_time'=>time()]);
        }else
            model('click')->insert(['ip'=>$ip,'articleId'=>$id,'create_time'=>time()]);

        return $this->fetch('',[
        		'post' =>$post,
        		'likeNum' =>$likeNum,
                'controller'=>'post',
                'title' =>$post->title.'-----刘先享LXX的个人博客,LXX,GoAheadLxx,goaheadlxx',
                'topTags'=>$topTags,
        	]);
    }
}
