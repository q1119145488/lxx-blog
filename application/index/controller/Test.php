<?php
namespace app\index\controller;
use think\Controller;

require_once $_SERVER['DOCUMENT_ROOT'].'/xunsearch/sdk/php/lib/XS.php';
class Test extends Controller
{
	function addIndex(){
		$articles = model('Article')->where('status',1)->select();
		$xs = new \XS('demo'); // 建立 XS 对象，项目名称为：demo
		$index = $xs->index; // 获取 索引对象
		
		foreach ($articles as $key => $article) {
			$doc = new \XSDocument;
			$data = [
				'pid'=> $article['id'],
				'subject'=> $article['title'],
				'description'=>$article['description'],
				'message'=> $article['content'],
				'chrono'=> $article['create_time'],
			];
			$doc->setFields($data);
 			$index->add($doc);
		}
		
	}
	function delIndex(){
		$xs = new \XS('demo'); // 建立 XS 对象，项目名称为：demo
		$index = $xs->index; // 获取 索引对象
		$index->clean();
	}
	function index(){

		$data = array(
		    'pid' => 234, // 此字段为主键，必须指定
		    'subject' => '测试文档的标题',
		    'message' => '测试文档的内容部分',
		    'chrono' => time()
		);
		 
	 	$xs = new \XS('demo'); // 建立 XS 对象，项目名称为：demo
		$search = $xs->search;
		

		$query = 'js'; // 这里的搜索语句很简单，就一个短语
 
		$search->setQuery($query); // 设置搜索语句
		$search->addWeight('subject', $query); // 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
		//$search->setLimit(5, 10); // 设置返回结果最多为 5 条，并跳过前 10 条
		 
		$docs = $search->search(); // 执行搜索，将搜索结果文档保存在 $docs 数组中
		$count = $search->count(); // 获取搜索结果的匹配总数估算值
		$data = [];

		foreach ($docs as $key => $value) {
			$data[] = $value;
		}
		print_r($data);
	}
}