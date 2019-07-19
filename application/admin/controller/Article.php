<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Request;
class Article extends Base
{
    public function index()
    {
    	$Article = model('Article')->ArticleList(7);

        if($Article){
            for($i = 0;$i<count($Article);$i++){
                $id = $Article[$i]['id'];
                $Article[$i]['clickNums'] = model('click')->where('articleId',$id)->count('id');
            }
        }

        return $this->fetch('',[
        			'Article' =>$Article,
                    'title' => 'Article List',
        			'controller' => strtolower(request()->controller() )
        		]);
    }

    public function addArticle(){
    	
		return $this->fetch('addArticle',[
                'title' => 'Add Article',
				'controller' => strtolower(request()->controller() )
			]);
    }

    public function editArticle(){

        $id = Request::instance()->param();
        $article = model('Article')->get($id);
        $tags = model('Tags')->getArticleTags($id['id']);
        return $this->fetch('',[
                'Article' => $article,
                'title' => $article['title'],
                'tags' =>$tags,
                'controller' => strtolower(request()->controller() )
            ]);
    }

    public function status(){
        $data = Request::instance()->param();
        $res = model('Article')->save(['status'=>$data['status']],['id'=>$data['id']]);
         if($res) {
            return alert('状态修改成功',$_SERVER["HTTP_REFERER"],6,3);
        }else {
            return alert('状态修改失败',$_SERVER["HTTP_REFERER"],5,3);
        }
    }


}
