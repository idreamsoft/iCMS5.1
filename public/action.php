<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
$do		= $_GET['do'];
$action	= $_GET['action'];
$id		= (int)$_GET['id'];
$mid	= (int)$_GET['mid'];
if(empty($mid)) {
    $__TABLE__='article';
}else {
	$model	= $iCMS->getCache('system/models.cache',$mid);
	$__TABLE__	= $model['tbn'];
}
switch ($do) {
	case 'forumAuth':
		$password	= $_POST['password'];
		$fid		= (int)$_POST['fid'];
		$forward	= $_POST['forward'];
		$F        	= $iCMS->getCache('system/forum.cache',$fid);
		if($F['password']==$password){
			$json="{state:'1',msg:'".$iCMS->language('forumAuth:success')."'}";
			set_cookie('forumAuth_'.$fid,authcode($fid.'#=iCMS!=#'.md5($F['password']),'ENCODE'));
		}else{
			$json="{state:'0',msg:'".$iCMS->language('forumAuth:failed')."'}";
		}
		die($json);
    case 'digg':
        if(in_array($action,array('good','bad'))) {
            $aTime=(time()-get_cookie('digg_'.$id)>$iCMS->config['diggtime'])?true:false;
            if($aTime) {
                set_cookie('digg_'.$id,time());
                if($id && iCMS_DB::query("UPDATE `#iCMS@__$__TABLE__` SET `$action` = $action+1  WHERE id='$id'")) {
                    $json="{state:'1'}";
                }
            }else {
                $json="{state:'0',msg:'".$iCMS->language('digged')."' }";
            }
            jsonp($json,$_GET['callback']);
        }
        if($action=='show') {
            $digg=iCMS_DB::getValue("SELECT digg FROM `#iCMS@__$__TABLE__` WHERE id='$id' LIMIT 1");
            echo "document.write('{$digg}');\r\n";
        }
        break;
    case 'hits':
		$fid = (int)$_GET['fid'];
		if($fid){
//			error_reporting(E_ALL ^ E_NOTICE);
//			iCMS_DB::$show_errors=true;
	    	$ymd	= date("Ymd");
	    	$ymd2	= date("Y-m-d");
	    	list($y,$m,$d)=explode('-',$ymd2);
	   		$statId=iCMS_DB::getValue("SELECT id FROM `#iCMS@__stat` WHERE `ymd`='$ymd' AND `indexId`='$id' AND `fid`='$fid' AND `type`='0'");
	    	if(empty($statId)){
	    		iCMS_DB::query("INSERT INTO `#iCMS@__stat` (`indexId`, `fid`, `val`, `d`, `m`, `y`, `ymd`, `dateline`, `type`)VALUES ('$id', '$fid', '1', '$d', '$m', '$y', '$ymd', '".time()."', '0');");
	    	}else{
	    		iCMS_DB::query("UPDATE `#iCMS@__stat` SET `val`=val+1 WHERE `id` ='$statId' LIMIT 1");
	    	}
		}
        iCMS_DB::query("UPDATE `#iCMS@__$__TABLE__` SET hits=hits+1 WHERE `id` ='$id' LIMIT 1");
        if($action=='show') {
            $hits=iCMS_DB::getValue("SELECT hits FROM `#iCMS@__$__TABLE__` WHERE id='$id'");
            echo "document.write('{$hits}');\r\n";
        }
        break;
    case 'comment':
        if(in_array($action,array('up','down'))) {
            UA($action,(int)$_GET['cid']);
        }
        if($action=='show') {
            if($iCMS->config['iscomment']) {
                $comments=iCMS_DB::getValue("SELECT comments FROM `#iCMS@__$__TABLE__` WHERE id='$id'  LIMIT 1");
                echo "document.write('{$comments}');\r\n";
            }
        }
        break;
}
function UA($act,$cid) {
    global $iCMS;
    $cookietime=$iCMS->config['diggtime'];
    $ajax=intval($_GET['ajax']);
    $cTime=(time()-get_cookie($cid.'_up')>$cookietime && time()-get_cookie($cid.'_against')>$cookietime)?true:false;
    if($cTime) {
        set_cookie($cid.'_'.$act,time(),$cookietime);
        if($cid && iCMS_DB::query("UPDATE `#iCMS@__comment` SET `{$act}` = {$act}+1  WHERE `id` ='$cid'")) {
            $ajax?jsonp("{state:'1'}",$_GET['callback']):_Header($iCMS->config['publicURL']."/comment.php?indexId=".$id);
        }
    }else {
        $ajax?jsonp("{state:'0',msg:'".$iCMS->language('digged')."' }",$_GET['callback']):alert($iCMS->language('digged'));
    }
}
function jsonp($json,$callback) {
    echo $callback.'('.$json.')';
    exit;
}
