<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/patch.class.php';
class iPatch extends AdminCP {
	function doinstall($download=false){
		$a	= patch::init(isset($_GET['force'])?true:false);
		if(empty($a)){
			echo "iCMS版本目前是最新版本:iCMS ".iCMS_VER." [".iCMS_RELEASE."]";
			return;
		}
		$msg='';
		if($download)
			$msg.= patch::download($a[1]);//下载

		$msg.= patch::update($a[1]);//更新
		
		if($a[2])
			$msg.= patch::run($a[2]);//执行升级程序

		include admincp::tpl("ipatch");
	}
	function doupdate(){
		$this->doinstall(true);
	}
	function doajax(){
		$a	= patch::init();
		if(empty($a)){
			echo $_GET['callback']."({state:'0'})";
			return;
		}
    	switch($this->iCMS->config['autopatch']){
    		case "1"://自动下载,安装时询问
	    		patch::download($a[1]);
	    		echo $_GET['callback']."({state:'1',msg:'".$a[3]."发现iCMS最新版本<br />iCMS ".$a[0]." [".$a[1]."]<br /><br />您当前使用的iCMS版本<br />iCMS ".iCMS_VER." [".iCMS_RELEASE."]<br /><br />新版本已经下载完成!! 是否现在更新?'})";
    		break;
    		case "2"://不自动下载更新,有更新时提示
	     		echo $_GET['callback']."({state:'2',msg:'".$a[3]."发现iCMS最新版本<br />iCMS ".$a[0]." [".$a[1]."]<br /><br />您当前使用的iCMS版本<br />iCMS ".iCMS_VER." [".iCMS_RELEASE."]<br /><br />请更新你的iCMS!!!'})";
    		break;
    	}
    	
	}
}
