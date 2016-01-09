<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class ajax extends UserCP {
	function docontentAttr(){
		$fid=$_GET['fid'];
		include_once(iPATH.'include/forum.class.php');
		$forum =new forum();
		$contentAttr =  unserialize($forum->forum[$fid]['contentAttr']);
    	if($contentAttr)foreach((array)$contentAttr AS $caKey=>$ca){
    		echo '<tr><td class="td40">'.$ca['name'].':</td><td colspan="3"><textarea  rows="6" onkeyup="textareasize(this)" name="metadata['.$ca['key'].']" cols="50" class="tarea"></textarea></td></tr>';
    	}
	}
}
