<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Index extends Base
{
    public function index()
    {
    	$pv = model('pageView')->pvList(7);
    	$time = date('y-m-d',time());

    	$todayPv = Db::query("select count('today') AS num from (select FROM_UNIXTIME(create_time,'%y-%m-%d') as `today` from page_view ) t where `today` = '$time'");	
    	$todayPv = $todayPv[0]['num'];
    	$allPv = model('pageView')->count('id');
    	//asd(model('pageView')->getLastSql());
        return $this->fetch('',[
        		'title' => 'Home',
        		'pv'=>$pv,
        		'todayPv'=>$todayPv,
        		'allPv'=>$allPv,
        		'controller' => strtolower(request()->controller() )
        	]);
    }
}
